@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-700">All Customers</h3>
        <div class="flex items-center space-x-2">
            <input type="text" placeholder="Search customers..." class="border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary">
            <button class="bg-gray-800 text-white px-4 py-2 rounded-sm text-sm font-bold">Search</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Orders</th>
                    <th class="px-6 py-4">Total Spend</th>
                    <th class="px-6 py-4">Registered At</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50 group">
                    <td class="px-6 py-4">
                        <div class="font-bold text-blue-600 hover:underline cursor-pointer">
                            <a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $customer->email }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $customer->orders_count }}</td>
                    <td class="px-6 py-4 font-bold text-gray-700 uppercase">
                        ${{ number_format($customer->total_spend, 2) }}
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-500 hover:underline text-xs">Edit</a>
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $customers->links() }}
    </div>
</div>
@endsection
