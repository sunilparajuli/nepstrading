<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code.');
        }

        $cart = session()->get('cart', []);
        $subtotal = array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['qty']), 0);

        if (!$coupon->isValid($subtotal)) {
            $msg = 'This coupon is not valid.';
            if ($coupon->min_order && $subtotal < $coupon->min_order) {
                $msg = "Minimum order of \${$coupon->min_order} required for this coupon.";
            }
            return redirect()->back()->with('error', $msg);
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'amount' => $coupon->amount,
            'free_shipping' => $coupon->free_shipping,
        ]);

        return redirect()->back()->with('success', "Coupon '{$coupon->code}' applied!");
    }

    public function remove()
    {
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Coupon removed.');
    }
}
