<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\NewOrderAdmin;
use Illuminate\Support\Facades\Log;

class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()
            ->with('items.product:id,name,slug,image')
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        $order->load('items.product');

        return response()->json($order);
    }

    public function store(Request $request)
    {
        Log::info('Checkout API Started', ['user_id' => $request->user()->id, 'data' => $request->all()]);
        
        if (\App\Models\SiteSetting::getValue('maintenance_mode', '0') == '1') {
            return response()->json([
                'message' => 'The store is currently under maintenance. Please try again later.'
            ], 503);
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
            'shipping_state' => 'nullable|string|max:50',
            'shipping_postcode' => 'nullable|string|max:20',
            'order_notes' => 'nullable|string',
            'payment_method' => 'nullable|string|max:50',
            'shipping_total' => 'nullable|numeric',
            'shipping_name' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        try {
            DB::beginTransaction();
            $subtotal = (float)$cart->items->sum(fn($item) => $item->price * $item->qty);

            if ($subtotal < 69) {
                return response()->json([
                    'message' => 'You must have an order with a minimum of $69.00 to place your order.'
                ], 422);
            }

            // Discount
            $discount = 0;
            $couponCode = null;
            if ($cart->coupon_code) {
                $coupon = Coupon::where('code', $cart->coupon_code)->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponCode = $coupon->code;
                }
            }

            // Tax
            $taxableAmount = max(0, $subtotal - $discount);
            $state = $request->shipping_state ?? $validated['billing_postcode'];
            $taxRate = TaxRate::getRate($state, 'AU');
            $taxTotal = 0;
            $taxName = null;
            if ($taxRate) {
                $taxTotal = round($taxableAmount * ($taxRate->rate / 100), 2);
                $taxName = $taxRate->name;
            }

            $shippingTotal = (float)($request->shipping_total ?? 0);
            $total = $taxableAmount + $shippingTotal + $taxTotal;

            $order = Order::create(array_merge($validated, [
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_total' => $shippingTotal,
                'shipping_name' => $request->shipping_name ?? 'Standard Shipping',
                'tax_total' => $taxTotal,
                'tax_name' => $taxName,
                'coupon_code' => $couponCode,
                'discount_total' => $discount,
                'total' => $total,
                'currency' => 'AUD',
                'payment_method' => $request->payment_method ?? 'bacs',
            ]));

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'total' => $item->price * $item->qty,
                ]);

                if ($item->product->manage_stock) {
                    $item->product->decrement('stock_quantity', $item->qty);
                }
            }

            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('usage_count');
            }

            // Clear cart
            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);

            DB::commit();

            // Send emails
            try {
                $order->load('items.product');
                Mail::to($order->billing_email)->queue(new OrderConfirmation($order));
                Mail::to(config('mail.from.address', 'admin@nepstrading.com'))->queue(new NewOrderAdmin($order));
            } catch (\Exception $e) {
                // Don't fail the order
            }

            return response()->json($order->load('items.product'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Save Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to create order: ' . $e->getMessage()], 500);
        }
    }

    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if (!in_array($order->status, ['pending'])) {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Order cancelled.', 'order' => $order]);
    }
}
