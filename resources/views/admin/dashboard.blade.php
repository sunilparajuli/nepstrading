@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Sales</div>
        <div class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_sales'], 2) }}</div>
    </div>
    <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Total Orders</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['orders_count'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Products</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['products_count'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Customers</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['customers_count'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-sm shadow-sm border-l-4 border-yellow-400">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-yellow-600">Pending Orders</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-sm shadow-sm border-l-4 border-blue-400">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-blue-600">Processing Orders</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['processing_orders'] }}</div>
    </div>
    <div class="bg-white p-6 rounded-sm shadow-sm border-l-4 border-green-400">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-green-600">Completed Orders</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['completed_orders'] }}</div>
    </div>
</div>

<div class="bg-white rounded-sm shadow-sm border border-gray-100 mb-8">
    <div class="p-4 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">Recent Orders</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-3">Order</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($stats['recent_orders'] as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-blue-600">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] uppercase font-bold 
                            {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-400 hover:text-gray-600">👁️</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
