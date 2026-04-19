<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search Filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock Filter
        if ($request->filled('stock')) {
            if ($request->stock === 'in') {
                $query->where(function($q) {
                    $q->where('manage_stock', false)
                      ->orWhere('stock_quantity', '>', 0);
                });
            } elseif ($request->stock === 'out') {
                $query->where('manage_stock', true)
                      ->where('stock_quantity', '<=', 0);
            }
        }

        // Popular/Seasonal Tags
        if ($request->boolean('popular')) {
            $query->where('is_popular', true);
        }
        if ($request->boolean('seasonal')) {
            $query->where('is_seasonal', true);
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'name_asc': $query->orderBy('name', 'asc'); break;
            case 'latest': $query->latest(); break;
            default: $query->orderBy('id', 'desc'); break;
        }

        $products = $query->with('categories')->paginate(12);
        return view('shop.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['categories', 'attributes.attribute', 'variations']);
        return view('shop.show', compact('product'));
    }
}
