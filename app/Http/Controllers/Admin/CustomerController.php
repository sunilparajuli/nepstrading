<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        // Add total spend to each customer (optional: could be optimized with a query)
        foreach($customers as $customer) {
            $customer->total_spend = Order::where('user_id', $customer->id)->sum('total');
        }

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->is_admin) {
            return redirect()->route('admin.customers.index')->with('error', 'Cannot view admin details here.');
        }

        $orders = Order::where('user_id', $customer->id)->latest()->get();
        $total_spend = $orders->sum('total');

        return view('admin.customers.show', compact('customer', 'orders', 'total_spend'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
