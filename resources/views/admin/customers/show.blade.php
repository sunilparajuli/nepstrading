@extends('layouts.admin')

@section('title', 'Customer Details: ' . $customer->name)

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Left Column: Profile Info -->
    <div class="w-full lg:w-1/3 space-y-8">
        <div class="bg-white p-6 rounded-sm shadow-sm border border-gray-100">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-16 h-16 rounded-full bg-primary text-black flex items-center justify-center text-2xl font-bold">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $customer->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                </div>
            </div>
            
            <div class="border-t border-gray-50 pt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Total Spend</label>
                    <p class="text-lg font-bold text-gray-800">${{ number_format($total_spend, 2) }}</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Orders Count</label>
                    <p class="text-lg font-bold text-gray-800">{{ $orders->count() }}</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Registered At</label>
                    <p class="text-sm text-gray-600">{{ $customer->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            
            <div class="mt-8 flex space-x-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="flex-grow bg-blue-600 text-white text-center py-2 rounded-sm text-sm font-bold hover:bg-blue-700 transition-colors">Edit Profile</a>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 text-red-500 px-4 py-2 rounded-sm text-sm font-bold border border-red-100 hover:bg-red-100 transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Order History -->
    <div class="w-full lg:w-2/3">
        <div class="bg-white rounded-sm shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-50">
                <h3 class="font-bold text-gray-700">Order History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-blue-600">
                                <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:underline text-xs">View</a>
                            </td>
                        </tr>
                        @endforeach
                        @if($orders->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 italic">No orders found for this customer.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
