@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Category: {{ $category->name }}</h2>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100 space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border border-gray-200 rounded-sm text-sm p-4 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full border border-gray-200 rounded-sm text-sm p-4 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none font-mono">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Image URL (Optional)</label>
                <input type="text" name="image" value="{{ old('image', $category->image) }}" class="w-full border border-gray-200 rounded-sm text-sm p-4 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="https://...">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Parent Category</label>
                <select name="parent_id" class="w-full border border-gray-200 rounded-sm text-sm p-4 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none">
                    <option value="">None (Top Level)</option>
                    @foreach($parentCategories as $parent)
                        @if($parent->id !== $category->id)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Category Icon / Image</label>
                @if($category->image)
                    <div class="mb-4 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-20 h-20 rounded-sm object-cover border border-gray-200" alt="">
                        <span class="text-[10px] text-gray-400">Current Image</span>
                    </div>
                @endif
                <input type="file" name="image" class="w-full border border-gray-200 rounded-sm text-sm p-4 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                <p class="text-[10px] text-gray-400 mt-2 italic">Leave empty to keep existing image. Recommended: 200x200px</p>
            </div>
            <div class="flex items-center gap-4 pt-6 border-t border-gray-50">
                <button type="submit" class="bg-primary text-black px-8 py-4 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-black hover:text-primary transition-all shadow-md">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="text-gray-400 text-xs font-bold uppercase hover:text-gray-800 transition-colors">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
