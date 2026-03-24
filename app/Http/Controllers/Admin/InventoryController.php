<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::where('manage_stock', true)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(50);

        return view('admin.inventory.index', compact('products'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock_quantity' => $request->stock_quantity,
            'stock_status' => $request->stock_quantity > 0 ? 'instock' : 'outofstock'
        ]);

        return back()->with('success', "Stock updated for {$product->name}");
    }
}
