<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\TaxRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\NewOrderAdmin;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['qty']);
        }, 0);

        if ($subtotal < 69) {
            return redirect()->route('cart.index')->with('error', 'You must have an order with a minimum of $69.00 to place your order.');
        }

        $shipping = session()->get('shipping_info', ['cost' => 0, 'name' => 'Free Shipping']);
        $location = session()->get('shipping_location');
        $coupon = session('coupon');

        // Calculate discount
        $discount = 0;
        if ($coupon) {
            $discount = $coupon['type'] === 'percentage'
                ? round($subtotal * ($coupon['amount'] / 100), 2)
                : min($coupon['amount'], $subtotal);
            if ($coupon['free_shipping'] ?? false) {
                $shipping['cost'] = 0;
                $shipping['name'] = 'Free Shipping (Coupon)';
            }
        }

        // Calculate tax
        $taxableAmount = max(0, $subtotal - $discount);
        $taxRate = TaxRate::getRate($location['state'] ?? null, $location['country'] ?? 'AU');
        $taxTotal = 0;
        $taxName = null;
        if ($taxRate) {
            $taxTotal = round($taxableAmount * ($taxRate->rate / 100), 2);
            $taxName = $taxRate->name . ' (' . $taxRate->rate . '%)';
            if ($taxRate->shipping && $shipping['cost'] > 0) {
                $taxTotal += round($shipping['cost'] * ($taxRate->rate / 100), 2);
            }
        }

        $total = $taxableAmount + ($shipping['cost'] ?? 0) + $taxTotal;

        $states = \Illuminate\Support\Facades\Cache::remember('location_states', 86400, function () {
            return \App\Models\Location::states()->where('is_enabled', true)->get();
        });

        return view('shop.checkout', compact('cart', 'subtotal', 'total', 'shipping', 'location', 'discount', 'coupon', 'taxTotal', 'taxName', 'states'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_postcode' => 'required|string|max:20',
            'billing_phone' => 'required|string|max:20',
            'billing_email' => 'required|email|max:255',
            'shipping_first_name' => 'nullable|string|max:255',
            'shipping_last_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_postcode' => 'nullable|string|max:20',
            'order_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = array_reduce($cart, function($carry, $item) {
                return $carry + ($item['price'] * $item['qty']);
            }, 0);

            if ($subtotal < 69) {
                return redirect()->route('cart.index')->with('error', 'You must have an order with a minimum of $69.00 to place your order.');
            }

            $shipping = session()->get('shipping_info', ['cost' => 0, 'name' => null]);
            $location = session()->get('shipping_location');
            $coupon = session('coupon');

            // Discount
            $discount = 0;
            $couponCode = null;
            if ($coupon) {
                $couponCode = $coupon['code'];
                $discount = $coupon['type'] === 'percentage'
                    ? round($subtotal * ($coupon['amount'] / 100), 2)
                    : min($coupon['amount'], $subtotal);
                if ($coupon['free_shipping'] ?? false) {
                    $shipping['cost'] = 0;
                }
            }

            // Tax
            $taxableAmount = max(0, $subtotal - $discount);
            $taxRate = TaxRate::getRate($location['state'] ?? null, $location['country'] ?? 'AU');
            $taxTotal = 0;
            $taxName = null;
            if ($taxRate) {
                $taxTotal = round($taxableAmount * ($taxRate->rate / 100), 2);
                $taxName = $taxRate->name;
                if ($taxRate->shipping && $shipping['cost'] > 0) {
                    $taxTotal += round($shipping['cost'] * ($taxRate->rate / 100), 2);
                }
            }

            $total = $taxableAmount + $shipping['cost'] + $taxTotal;

            $order = Order::create(array_merge($validated, [
                'user_id' => auth()->id(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_total' => $shipping['cost'],
                'shipping_name' => $shipping['name'] ?? null,
                'tax_total' => $taxTotal,
                'tax_name' => $taxName,
                'coupon_code' => $couponCode,
                'discount_total' => $discount,
                'total' => $total,
                'currency' => 'AUD',
            ]));

            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if (!$product) {
                    unset($cart[$productId]);
                    session()->put('cart', $cart);
                    throw new \Exception("Product '{$item['name']}' is no longer available.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['qty'],
                ]);

                // Decrement stock if managed
                if ($product->manage_stock) {
                    $product->decrement('stock_quantity', $item['qty']);
                }
            }

            // Increment coupon usage
            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('usage_count');
            }

            DB::commit();

            // Send emails (non-blocking)
            try {
                $order->load('items.product');
                Mail::to($order->billing_email)->queue(new OrderConfirmation($order));
                Mail::to(config('mail.from.address', 'admin@nepstrading.com'))->queue(new NewOrderAdmin($order));
            } catch (\Exception $e) {
                // Don't fail the order if email fails
            }

            session()->forget(['cart', 'coupon']);

            return redirect()->route('products.index')->with('success', "Order #{$order->id} placed successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
