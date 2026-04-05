<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display the customer dashboard with recent orders.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->get();

        return view('customer.dashboard', compact('user', 'orders'));
    }

    /**
     * Display the details of a specific order.
     */
    public function showOrder($id)
    {
        $user = Auth::user();
        $order = Order::with('items.product')->where('user_id', $user->id)->findOrFail($id);

        return view('customer.order', compact('order'));
    }
}
