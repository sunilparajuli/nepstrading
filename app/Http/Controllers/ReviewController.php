<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:2000',
        ]);

        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        $verifiedPurchase = auth()->user()->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'verified_purchase' => $verifiedPurchase,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Review submitted! It will appear after moderation.');
    }
}
