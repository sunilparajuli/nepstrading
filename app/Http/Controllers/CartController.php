<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\ShippingLocation;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = array_reduce($cart, function($carry, $item) {
            return $carry + ($item['price'] * $item['qty']);
        }, 0);

        // Get shipping info
        $shipping = $this->getShippingInfo($subtotal);

        // Apply coupon discount
        $discount = 0;
        $coupon = session('coupon');
        if ($coupon) {
            $discount = $coupon['type'] === 'percentage'
                ? round($subtotal * ($coupon['amount'] / 100), 2)
                : min($coupon['amount'], $subtotal);
            if ($coupon['free_shipping'] ?? false) {
                $shipping['cost'] = 0;
                $shipping['name'] = 'Free Shipping (Coupon)';
            }
        }

        $total = max(0, $subtotal - $discount) + ($shipping['cost'] ?? 0);

        return view('cart.index', compact('cart', 'subtotal', 'total', 'shipping', 'discount'));
    }

    public function setLocation(Request $request)
    {
        $location = $request->only(['state', 'country', 'postcode']);
        session()->put('shipping_location', $location);
        
        // Find best rate
        $this->getShippingInfo(0); // Trigger session update

        return redirect()->back()->with('success', 'Location updated and shipping recalculated.');
    }

    public function autoDetectLocation()
    {
        try {
            $response = Http::get('http://ip-api.com/json');
            if ($response->successful()) {
                $data = $response->json();
                $location = [
                    'state' => $data['region'], // NSW, VIC etc.
                    'country' => $data['countryCode'], // AU
                    'postcode' => $data['zip'],
                ];
                session()->put('shipping_location', $location);
                return redirect()->back()->with('success', 'Location detected: ' . $data['city'] . ', ' . $data['region']);
            }
        } catch (\Exception $e) {
            // Fallback
        }
        return redirect()->back()->with('error', 'Could not detect location automatically.');
    }

    private function getShippingInfo($subtotal)
    {
        $location = session()->get('shipping_location');
        
        if (!$location) {
            return ['cost' => 0, 'name' => 'Please calculate shipping', 'method' => null];
        }

        // 1. Try to find a zone by state/country
        $foundLocation = ShippingLocation::where(function($q) use ($location) {
            $q->where('type', 'state')->where('code', $location['state']);
        })->orWhere(function($q) use ($location) {
            $q->where('type', 'country')->where('code', $location['country']);
        })->first();

        if ($foundLocation) {
            $rate = ShippingRate::where('shipping_zone_id', $foundLocation->shipping_zone_id)
                                ->orderBy('cost', 'asc') // Pick cheapest
                                ->first();
            
            if ($rate) {
                $info = ['cost' => (float)$rate->cost, 'name' => $rate->name, 'method' => $rate->id];
                session()->put('shipping_info', $info);
                return $info;
            }
        }

        return ['cost' => 0, 'name' => 'No shipping available for your area', 'method' => null];
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);
        $qty = $request->input('qty', 1);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "qty" => $qty,
                "price" => $product->sale_price ?? $product->price,
                "image" => $product->image ?? 'https://placehold.co/100x100?text=' . urlencode($product->name)
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $cart = session()->get('cart', []);
            $subtotal = array_reduce($cart, function($carry, $item) {
                return $carry + ($item['price'] * $item['qty']);
            }, 0);

            // Re-render the cart sidebar partial or the specific section
            // For now, let's just return the subtotal and count, 
            // and we'll handle the sidebar update by fetching a partial if needed.
            // Actually, returning the whole HTML for the sidebar is easier.
            
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => count($cart),
                'cart_total' => number_format($subtotal, 2),
                'cart_html' => view('partials.cart_sidebar_contents')->render()
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $qty = max(1, $request->input('qty', 1));

        if(isset($cart[$id])) {
            $cart[$id]['qty'] = $qty;
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $cart = session()->get('cart', []);
            $subtotal = array_reduce($cart, function($carry, $item) {
                return $carry + ($item['price'] * $item['qty']);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_count' => count($cart),
                'cart_total' => number_format($subtotal, 2),
                'cart_html' => view('partials.cart_sidebar_contents')->render()
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $cart = session()->get('cart', []);
            $subtotal = array_reduce($cart, function($carry, $item) {
                return $carry + ($item['price'] * $item['qty']);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => 'Product removed successfully!',
                'cart_count' => count($cart),
                'cart_total' => number_format($subtotal, 2),
                'cart_html' => view('partials.cart_sidebar_contents')->render()
            ]);
        }

        return redirect()->back()->with('success', 'Product removed successfully!');
    }
}
