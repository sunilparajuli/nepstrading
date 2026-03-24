<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q', '');
        $category = $request->input('category');

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $query = Product::where('name', 'like', '%' . $q . '%')
            ->orWhere('description', 'like', '%' . $q . '%');

        if ($category) {
            $query->whereHas('categories', function ($qb) use ($category) {
                $qb->where('categories.id', $category);
            });
        }

        $products = $query->with('categories')
            ->limit(8)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => number_format($p->sale_price ?? $p->price, 2),
                    'sale_price' => $p->sale_price ? number_format($p->sale_price, 2) : null,
                    'original_price' => $p->sale_price ? number_format($p->price, 2) : null,
                    'image' => $p->image ?: 'https://placehold.co/100x100/f7f5ed/1f332a?text=' . urlencode($p->name),
                    'category' => $p->categories->first()?->name ?? '',
                    'url' => route('products.show', $p),
                ];
            });

        return response()->json($products);
    }
}
