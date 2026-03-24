@extends('layouts.admin')

@section('title', 'Edit Attribute: ' . $attribute->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <h3 class="font-bold text-gray-700 uppercase tracking-wide text-sm">Edit Attribute</h3>
            <a href="{{ route('admin.attributes.index') }}" class="text-xs font-bold text-blue-600 hover:underline">← Back to List</a>
        </div>
        
        <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $attribute->name) }}" required 
                        class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                    <p class="text-[10px] text-gray-400 mt-1 italic">Name for the attribute (e.g. Color, Size).</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $attribute->slug) }}" required 
                        class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Type</label>
                    <select name="type" class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                        <option value="select" {{ $attribute->type == 'select' ? 'selected' : '' }}>Select</option>
                        <option value="color" {{ $attribute->type == 'color' ? 'selected' : '' }}>Color</option>
                        <option value="image" {{ $attribute->type == 'image' ? 'selected' : '' }}>Image</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Weight</label>
                    <input type="number" name="weight" value="{{ old('weight', $attribute->weight) }}" 
                        class="w-full border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:ring-primary focus:border-primary">
                    <p class="text-[10px] text-gray-400 mt-1 italic">Determines display order (lower = first).</p>
                </div>
                <div class="pt-4">
                    <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-sm text-sm font-bold uppercase tracking-wider hover:bg-black transition-colors">Update Attribute</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
