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
        $data = $this->getCartViewData();
        $postcodes = \App\Models\ShippingLocation::where('type', 'postcode')
            ->distinct()
            ->orderBy('code')
            ->pluck('code');

        $states = \Illuminate\Support\Facades\Cache::remember('location_states', 3600, function () {
            $locs = \App\Models\Location::states()->where('is_enabled', true)->get()->map(function($l) {
                return (object)['id' => $l->id, 'name' => $l->name, 'code' => $l->code];
            });
            
            $zones = \App\Models\ShippingZone::where('is_enabled', true)->get()->map(function($z) {
                return (object)['id' => 'z' . $z->id, 'name' => $z->name, 'code' => $z->name];
            });
            
            return $locs->concat($zones)->unique('name');
        });
        
        $allLocations = \Illuminate\Support\Facades\Cache::remember('location_all', 86400, function () {
            return \App\Models\Location::where('is_enabled', true)->get();
        });

        return view('cart.index', array_merge($data, [
            'postcodes' => $postcodes,
            'states' => $states,
            'allLocations' => $allLocations
        ]));
    }

    private function getCartViewData()
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
        $minOrderMet = $subtotal >= 69;

        return [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'total' => $total,
            'shipping' => $shipping,
            'discount' => $discount,
            'minOrderMet' => $minOrderMet,
            'formatted_subtotal' => number_format($subtotal, 2),
            'formatted_total' => number_format($total, 2),
            'formatted_shipping_cost' => number_format($shipping['cost'] ?? 0, 2),
            'min_order_gap' => number_format(max(0, 69 - $subtotal), 2)
        ];
    }

    public function setLocation(Request $request)
    {
        $location = $request->only(['state', 'country', 'postcode', 'city']);
        session()->put('shipping_location', $location);
        
        // Find best rate
        $this->getShippingInfo(0); // Trigger session update

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge(
                ['success' => true, 'message' => 'Shipping location updated!'],
                $this->getCartViewData()
            ));
        }

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

        $userPostcode = trim($location['postcode'] ?? '');
        $userState = \App\Helpers\LocationHelper::normalizeState($location['state'] ?? '');
        $userCountry = strtoupper(trim($location['country'] ?? ''));

        // Fetch all postcode-type locations with caching
        $postcodeLocations = \Illuminate\Support\Facades\Cache::remember('shipping_postcode_locations', 86400, function() {
            return \App\Models\ShippingLocation::where('type', 'postcode')->with('zone.rates')->get();
        });

        $foundLocation = null;

        // 1. Try to find a zone by postcode (highest priority)
        foreach ($postcodeLocations as $loc) {
            if (\App\Helpers\LocationHelper::matchPostcode($userPostcode, $loc->code)) {
                $foundLocation = $loc;
                break;
            }
        }

        // 2. If no postcode match, try to find a zone by state/country
        if (!$foundLocation) {
            $foundLocation = \App\Models\ShippingLocation::where(function($q) use ($userState) {
                if ($userState) $q->where('type', 'state')->where('code', $userState);
                else $q->where('id', 0);
            })->orWhere(function($q) use ($userCountry) {
                if ($userCountry) $q->where('type', 'country')->where('code', $userCountry);
                else $q->where('id', 0);
            })->first();
        }

        if ($foundLocation && $foundLocation->zone) {
            $savedMethod = session('shipping_info.method');
            $rate = null;
            
            if ($savedMethod) {
                $rate = \App\Models\ShippingRate::where('shipping_zone_id', $foundLocation->shipping_zone_id)
                                    ->where('id', $savedMethod)
                                    ->first();
            }

            if (!$rate) {
                $rate = \App\Models\ShippingRate::where('shipping_zone_id', $foundLocation->shipping_zone_id)
                                    ->orderBy('cost', 'asc')
                                    ->first();
            }
            
            if ($rate) {
                $info = [
                    'cost' => (float)$rate->cost, 
                    'name' => $rate->name . ' (' . $foundLocation->zone->name . ')',
                    'zone' => $foundLocation->zone->name,
                    'method' => $rate->id
                ];
                session()->put('shipping_info', $info);
                return $info;
            }
        }

        return ['cost' => 0, 'name' => 'No shipping available for your area', 'method' => null];
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $variationId = $request->input('variation_id');
        $qty = $request->input('qty', 1);
        $cart = session()->get('cart', []);

        $cartKey = $variationId ? $productId . '-' . $variationId : $productId;
        $price = $product->sale_price ?? $product->price;
        $name = $product->name;
        $image = $product->image;

        if ($variationId) {
            $variation = \App\Models\ProductVariation::findOrFail($variationId);
            $price = $variation->sale_price ?? $variation->price;
            $image = $variation->image ?? $product->image;
            // Append variation attributes to name for clarity in cart
            $name .= ' (' . $variation->formatted_attributes . ')';
        }

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $qty;
        } else {
            $cart[$cartKey] = [
                "product_id" => $productId,
                "variation_id" => $variationId,
                "name" => $name,
                "qty" => $qty,
                "price" => $price,
                "image" => $image ?? 'https://placehold.co/100x100?text=' . urlencode($name)
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $data = $this->getCartViewData();
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => count($data['cart']),
                'cart_total' => $data['formatted_subtotal'],
                'cart_html' => view('partials.cart_sidebar_contents')->render()
            ], $data));
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
            $data = $this->getCartViewData();
            $item_subtotal = number_format($cart[$id]['price'] * $qty, 2);
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_count' => count($data['cart']),
                'item_subtotal' => $item_subtotal,
                'cart_html' => view('partials.cart_sidebar_contents')->render()
            ], $data));
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
            $data = $this->getCartViewData();
            return response()->json(array_merge([
                'success' => true,
                'message' => 'Product removed successfully!',
                'cart_count' => count($data['cart']),
                'cart_html' => view('partials.cart_sidebar_contents')->render()
            ], $data));
        }

        return redirect()->back()->with('success', 'Product removed successfully!');
    }
}

