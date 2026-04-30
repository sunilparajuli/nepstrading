@extends('layouts.admin')

@section('title', 'Brand Management')

@section('content')
<div class="bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
        <h2 class="text-lg font-bold">Brands</h2>
        <a href="{{ route('admin.brands.create') }}" class="bg-primary text-black px-4 py-2 rounded-sm text-sm font-black shadow-sm hover:shadow-md transition-shadow uppercase tracking-wider">Add New Brand</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Logo</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($brands as $brand)
                <tr class="hover:bg-gray-50 group">
                    <td class="px-6 py-4">
                        <img src="{{ $brand->logo ?? 'https://placehold.co/100x40' }}" class="h-8 object-contain">
                    </td>
                    <td class="px-6 py-4 font-bold">{{ $brand->name }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $brand->sort_order }}</td>
                    <td class="px-6 py-4">
                        @if($brand->is_enabled)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full">Enabled</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded-full">Disabled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end space-x-2">
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="text-blue-500 hover:underline text-[10px] font-bold uppercase">Edit</a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Delete this brand?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-[10px] font-bold uppercase">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6">
        {{ $brands->links() }}
    </div>
</div>
@endsection
