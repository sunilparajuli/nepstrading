<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user());
        $cart->load('items.product', 'items.variation');

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->qty);
        $discount = 0;

        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        return response()->json([
            'items' => $cart->items->map(fn($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_image' => $item->product->image,
                'product_slug' => $item->product->slug,
                'variation_id' => $item->variation_id,
                'variation' => $item->variation?->attribute_combination,
                'qty' => $item->qty,
                'price' => (float) $item->price,
                'total' => round($item->price * $item->qty, 2),
            ]),
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'coupon_code' => $cart->coupon_code,
            'total' => round(max(0, $subtotal - $discount), 2),
            'items_count' => $cart->items->sum('qty'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'qty' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->sale_price ?? $product->price;

        if ($request->variation_id) {
            $variation = ProductVariation::findOrFail($request->variation_id);
            $price = $variation->sale_price ?? $variation->price;
        }

        $cart = $this->getOrCreateCart($request->user());

        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->first();

        if ($existing) {
            $existing->increment('qty', $request->input('qty', 1));
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'variation_id' => $request->variation_id,
                'qty' => $request->input('qty', 1),
                'price' => $price,
            ]);
        }

        return $this->index($request);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate(['qty' => 'required|integer|min:1']);
        $cartItem->update(['qty' => $request->qty]);

        return $this->index($request);
    }

    public function remove(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== $request->user()->id) {
            abort(403);
        }

        $cartItem->delete();

        return $this->index($request);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid coupon code.'], 422);
        }

        $cart = $this->getOrCreateCart($request->user());
        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->qty);

        if (!$coupon->isValid($subtotal)) {
            return response()->json(['message' => 'This coupon is not valid for your order.'], 422);
        }

        $cart->update(['coupon_code' => $coupon->code]);

        return $this->index($request);
    }

    public function removeCoupon(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user());
        $cart->update(['coupon_code' => null]);

        return $this->index($request);
    }

    private function getOrCreateCart($user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }
}
