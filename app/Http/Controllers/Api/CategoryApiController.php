<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->with('children:id,name,slug,parent_id,image')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function show(Category $category)
    {
        $category->load('children', 'products');
        return response()->json($category);
    }
}
