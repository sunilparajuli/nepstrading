@extends('layouts.admin')

@section('title', 'Edit Brand')

@section('content')
<div class="max-w-2xl bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50">
        <h2 class="text-lg font-bold">Edit: {{ $brand->name }}</h2>
    </div>
    
    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-2">
            <label class="text-xs font-bold uppercase text-gray-500">Brand Name</label>
            <input type="text" name="name" value="{{ $brand->name }}" required class="w-full border-gray-200 rounded-sm text-sm focus:ring-primary focus:border-primary">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold uppercase text-gray-500">Logo</label>
            @if($brand->logo)
                <div class="mb-2 p-4 bg-gray-50 rounded-sm border border-gray-100 inline-block">
                    <img src="{{ $brand->logo }}" class="h-12 object-contain" alt="Current Logo">
                </div>
            @endif
            <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-bold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
        </div>

        <div class="flex gap-8">
            <div class="space-y-2 flex-1">
                <label class="text-xs font-bold uppercase text-gray-500">Sort Order</label>
                <input type="number" name="sort_order" value="{{ $brand->sort_order }}" class="w-full border-gray-200 rounded-sm text-sm focus:ring-primary focus:border-primary">
            </div>
            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="is_enabled" {{ $brand->is_enabled ? 'checked' : '' }} id="is_enabled" class="rounded-sm border-gray-300 text-primary focus:ring-primary">
                <label for="is_enabled" class="text-sm font-bold text-gray-700">Enabled</label>
            </div>
        </div>

        <div class="pt-4 flex gap-2">
            <button type="submit" class="bg-primary text-black px-8 py-3 rounded-sm text-xs font-black uppercase tracking-widest shadow-sm hover:shadow-md transition-all">Update Brand</button>
            <a href="{{ route('admin.brands.index') }}" class="px-8 py-3 rounded-sm text-xs font-black uppercase tracking-widest border border-gray-200 text-gray-500 hover:bg-gray-50 transition-all text-center">Cancel</a>
        </div>
    </form>
</div>
@endsection
