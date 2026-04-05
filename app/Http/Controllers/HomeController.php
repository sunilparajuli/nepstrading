<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\HomepageSection;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::active()->get()->map(function($section) {
            $data = $section->data ?? [];
            $limit = $data['limit'] ?? 4;
            
            switch($section->type) {
                case 'featured_products':
                    if (!empty($data['product_ids'])) {
                        $section->resolved_data = Product::with('categories')->whereIn('id', $data['product_ids'])->get();
                    } else {
                        $section->resolved_data = Product::with('categories')->where('is_seasonal', true)->limit($limit)->get();
                    }
                    break;
                case 'popular_products':
                    if (!empty($data['product_ids'])) {
                        $section->resolved_data = Product::with('categories')->whereIn('id', $data['product_ids'])->get();
                    } else {
                        $section->resolved_data = Product::with('categories')->where('is_popular', true)->limit($limit)->get();
                    }
                    break;
                case 'new_products':
                    if (!empty($data['product_ids'])) {
                        $section->resolved_data = Product::with('categories')->whereIn('id', $data['product_ids'])->get();
                    } else {
                        $section->resolved_data = Product::with('categories')->latest()->limit($limit)->get();
                    }
                    break;
                case 'categories':
                    $catLimit = empty($data['limit']) ? 100 : $data['limit'];
                    $section->resolved_data = Category::whereNull('parent_id')->limit($catLimit)->get();
                    break;
            }
            return $section;
        });

        // Fallback for old view if no sections exist
        if ($sections->isEmpty()) {
            $seasonalProducts = Product::where('is_seasonal', true)->limit(4)->get();
            $popularProducts = Product::where('is_popular', true)->limit(8)->get();
            $latestProducts = Product::latest()->limit(8)->get();
            return view('welcome', [
                'seasonalProducts' => $seasonalProducts,
                'popularProducts' => $popularProducts,
                'latestProducts' => $latestProducts,
                'sections' => collect([])
            ]);
        }

        return view('welcome', compact('sections'));
    }
}
