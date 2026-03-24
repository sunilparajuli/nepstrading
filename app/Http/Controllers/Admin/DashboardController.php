<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => Order::where('status', 'completed')->sum('total'),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'customers_count' => \App\Models\User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'recent_orders' => Order::latest()->take(5)->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
