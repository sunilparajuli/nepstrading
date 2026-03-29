<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('status', 'active')->get();
        $categories = Category::all();
        $pages = Page::where('status', 'published')->get();

        return response()->view('seo.sitemap', [
            'products' => $products,
            'categories' => $categories,
            'pages' => $pages,
        ])->header('Content-Type', 'text/xml');
    }
}
