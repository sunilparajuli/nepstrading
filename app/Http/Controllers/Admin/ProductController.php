<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('categories')->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Search by name or SKU
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $products = $query->paginate(20);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('terms')->get();
        return view('admin.products.create', compact('categories', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|unique:products,sku',
            'manage_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|in:instock,outofstock',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'deal_ends_at' => 'nullable|date',
            'deal_total_stock' => 'nullable|integer|min:0',
            'allow_add_to_cart' => 'nullable|boolean',
            'cart_disabled_message' => 'nullable|string',
        ]);

        // Auto-generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            $i = 1;
            $base = $validated['slug'];
            while (Product::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $base . '-' . $i++;
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        $validated['manage_stock'] = $request->has('manage_stock');
        $validated['allow_add_to_cart'] = $request->has('allow_add_to_cart');
        
        $product = Product::create($validated);
        
        if ($request->has('categories')) {
            $product->categories()->sync($request->input('categories'));
        }

        if ($request->has('attributes')) {
            $product->attributes()->sync($request->input('attributes'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $attributes = Attribute::with('terms')->get();
        return view('admin.products.edit', compact('product', 'categories', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|unique:products,slug,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|unique:products,sku,' . $product->id,
            'manage_stock' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|in:instock,outofstock',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'short_description' => 'nullable',
            'deal_ends_at' => 'nullable|date',
            'deal_total_stock' => 'nullable|integer|min:0',
            'allow_add_to_cart' => 'nullable|boolean',
            'cart_disabled_message' => 'nullable|string',
        ]);

        // Auto-generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            $i = 1;
            $base = $validated['slug'];
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $base . '-' . $i++;
            }
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($path);
        }

        $validated['manage_stock'] = $request->has('manage_stock');
        $validated['allow_add_to_cart'] = $request->has('allow_add_to_cart');

        $product->update($validated);

        if ($request->has('categories')) {
            $product->categories()->sync($request->input('categories'));
        }

        if ($request->has('attributes')) {
            $product->attributes()->sync($request->input('attributes'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
