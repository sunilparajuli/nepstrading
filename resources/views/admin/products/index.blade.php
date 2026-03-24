@extends('layouts.admin')

@section('title', 'All Products')

@section('content')
<div class="bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <a href="{{ route('admin.products.create') }}" class="bg-primary text-black px-4 py-2 rounded-sm text-sm font-black shadow-sm hover:shadow-md transition-shadow uppercase tracking-wider">Add New</a>
        </div>
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
            <select name="category" class="border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary w-full md:w-48">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <div class="flex w-full md:w-auto">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="border-gray-200 rounded-sm text-sm p-2 bg-gray-50 focus:ring-primary focus:border-primary w-full md:w-64">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-sm text-sm font-bold hover:bg-black transition-colors ml-1">Filter</button>
                @if(request()->anyFilled(['category', 'q']))
                    <a href="{{ route('admin.products.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-sm text-sm font-bold hover:bg-gray-200 transition-colors ml-1">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4"><input type="checkbox" class="rounded-sm border-gray-300"></th>
                    <th class="px-6 py-4">Image</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">SKU</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Categories</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 group">
                    <td class="px-6 py-4"><input type="checkbox" class="rounded-sm border-gray-300"></td>
                    <td class="px-6 py-4">
                        <img src="{{ $product->image ?? 'https://placehold.co/40x40' }}" class="w-10 h-10 object-cover rounded-sm border border-gray-100">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-blue-600 hover:underline cursor-pointer">
                            <a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                        </div>
                        <div class="flex space-x-2 text-[10px] opacity-0 group-hover:opacity-100 transition-opacity mt-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500">Edit</a>
                            <span class="text-gray-300">|</span>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Are you sure?')">Trash</button>
                            </form>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('products.show', $product) }}" target="_blank" class="text-gray-500">View</a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $product->sku ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="text-green-600 font-bold uppercase text-[10px]">In Stock</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->sale_price)
                            <span class="font-bold">${{ number_format($product->sale_price, 2) }}</span>
                            <div class="text-[10px] text-gray-400 line-through">${{ number_format($product->price, 2) }}</div>
                        @else
                            <span class="font-bold">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 uppercase text-[10px] font-bold">
                        @forelse($product->categories as $category)
                            <span class="block">{{ $category->name }}</span>
                        @empty
                            <span class="text-gray-300 italic">Uncategorized</span>
                        @endforelse
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-gray-400 hover:text-gray-600">✎</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
