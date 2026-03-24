<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistApiController extends Controller
{
    public function index(Request $request)
    {
        $items = Wishlist::with('product:id,name,slug,price,sale_price,image,stock_status')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product' => $item->product,
                'added_at' => $item->created_at,
            ]);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already in wishlist.'], 409);
        }

        $item = Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json($item->load('product'), 201);
    }

    public function destroy(Request $request, Wishlist $wishlist)
    {
        if ($wishlist->user_id !== $request->user()->id) {
            abort(403);
        }

        $wishlist->delete();

        return response()->json(['message' => 'Removed from wishlist.']);
    }
}
