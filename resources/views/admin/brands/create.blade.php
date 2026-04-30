@extends('layouts.admin')

@section('title', 'Add New Brand')

@section('content')
<div class="max-w-2xl bg-white rounded-sm shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-50">
        <h2 class="text-lg font-bold">New Brand</h2>
    </div>

    @if ($errors->any())
        <div class="p-6 bg-red-50 border-b border-red-100">
            <ul class="list-disc list-inside text-sm text-red-600 font-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf
        
        <div class="space-y-2">
            <label class="text-xs font-bold uppercase text-gray-500">Brand Name</label>
            <input type="text" name="name" required class="w-full border-gray-200 rounded-sm text-sm focus:ring-primary focus:border-primary" placeholder="e.g. Britannia">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold uppercase text-gray-500">Logo (Recommended: Transparent PNG, 200x80)</label>
            <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-bold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
        </div>

        <div class="flex gap-8">
            <div class="space-y-2 flex-1">
                <label class="text-xs font-bold uppercase text-gray-500">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="w-full border-gray-200 rounded-sm text-sm focus:ring-primary focus:border-primary">
            </div>
            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="is_enabled" checked id="is_enabled" class="rounded-sm border-gray-300 text-primary focus:ring-primary">
                <label for="is_enabled" class="text-sm font-bold text-gray-700">Enabled</label>
            </div>
        </div>

        <div class="pt-4 flex gap-2">
            <button type="submit" class="bg-primary text-black px-8 py-3 rounded-sm text-xs font-black uppercase tracking-widest shadow-sm hover:shadow-md transition-all">Save Brand</button>
            <a href="{{ route('admin.brands.index') }}" class="px-8 py-3 rounded-sm text-xs font-black uppercase tracking-widest border border-gray-200 text-gray-500 hover:bg-gray-50 transition-all text-center">Cancel</a>
        </div>
    </form>
</div>
@endsection
