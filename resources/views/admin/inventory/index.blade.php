@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('content')
<div class="px-8 py-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Product Stock Levels</h3>
            <div class="flex space-x-2">
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Low Stock (< 5)</span>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Medium Stock (5-20)</span>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Good Stock (> 20)</span>
            </div>
        </div>
        
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">SKU</th>
                    <th class="px-6 py-4 text-center">Current Stock</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Update Stock</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                @php
                    $stockColor = 'text-green-600 bg-green-50';
                    $stockBorder = 'border-green-100';
                    if($product->stock_quantity < 5) {
                        $stockColor = 'text-red-600 bg-red-50';
                        $stockBorder = 'border-red-100';
                    } elseif($product->stock_quantity <= 20) {
                        $stockColor = 'text-yellow-600 bg-yellow-50';
                        $stockBorder = 'border-yellow-100';
                    }
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $product->image ?: 'https://placehold.co/40x40' }}" class="w-10 h-10 rounded object-cover border border-gray-100" alt="">
                            <div>
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $product->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $product->sku ?: '—' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-3 py-1 rounded-lg border {{ $stockBorder }} {{ $stockColor }} font-bold text-sm">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter {{ $product->stock_status === 'instock' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($product->stock_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.inventory.update', $product) }}" method="POST" class="inline-flex items-center space-x-2">
                            @csrf
                            <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" min="0" 
                                class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <button type="submit" class="p-1 text-green-600 hover:bg-green-50 rounded transition-colors" title="Update">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="p-6 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
