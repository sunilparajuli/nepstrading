<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewApiController extends Controller
{
    public function index(Product $product)
    {
        $reviews = $product->approvedReviews()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => round($product->approvedReviews()->avg('rating') ?? 0, 1),
            'total_reviews' => $product->approvedReviews()->count(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string|max:2000',
        ]);

        $existing = Review::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'You have already reviewed this product.'], 409);
        }

        $verifiedPurchase = $request->user()->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'] ?? null,
            'verified_purchase' => $verifiedPurchase,
            'status' => 'pending',
        ]);

        return response()->json($review, 201);
    }
}
