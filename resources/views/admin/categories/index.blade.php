@extends('layouts.admin')

@section('title', 'Product Categories')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <!-- Add New Category Form -->
    <div class="w-full lg:w-1/3">
        <div class="bg-white p-8 rounded-sm shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-700 mb-6 uppercase tracking-wide text-xs border-b border-gray-50 pb-2">Add New Category</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all" required placeholder="Category name">
                        <p class="text-[10px] text-gray-400 mt-2 italic">The name is how it appears on your site.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none font-mono" placeholder="category-slug">
                        <p class="text-[10px] text-gray-400 mt-2 italic">Leave empty to auto-generate.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Image URL (Optional)</label>
                        <input type="text" name="image" value="{{ old('image') }}" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Parent Category</label>
                        <select name="parent_id" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none">
                            <option value="">None (Top Level)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-primary text-black px-6 py-4 rounded-sm text-xs font-black w-full uppercase tracking-widest hover:bg-black hover:text-primary transition-all shadow-sm">Add New Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category List Table -->
    <div class="w-full lg:w-2/3">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black border-b border-gray-100 tracking-widest">
                    <tr>
                        <th class="px-6 py-5 text-center w-12"><input type="checkbox" class="rounded-sm border-gray-300"></th>
                        <th class="px-6 py-5">Name</th>
                        <th class="px-6 py-5">Slug</th>
                        <th class="px-6 py-5">Parent</th>
                        <th class="px-6 py-5 text-center">Count</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                        function renderCategoryRow($cat, $level = 0) {
                            $prefix = str_repeat('— ', $level);
                            return [$cat, $prefix, $level];
                        }
                        
                        $flatList = [];
                        foreach($parentCategories as $parent) {
                            $flatList[] = renderCategoryRow($parent, 0);
                            foreach($allCategories->where('parent_id', $parent->id) as $child) {
                                $flatList[] = renderCategoryRow($child, 1);
                                foreach($allCategories->where('parent_id', $child->id) as $grandchild) {
                                    $flatList[] = renderCategoryRow($grandchild, 2);
                                }
                            }
                        }
                    @endphp

                    @forelse($flatList as [$category, $prefix, $level])
                    <tr class="hover:bg-blue-50/30 group transition-colors">
                        <td class="px-6 py-4 text-center"><input type="checkbox" class="rounded-sm border-gray-300"></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($category->image)
                                    <div class="relative group">
                                        <img src="{{ $category->image }}" class="w-10 h-10 rounded-sm object-cover border border-gray-100 shadow-sm">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-sm bg-gray-50 border border-dashed border-gray-200 flex items-center justify-center">
                                        <span class="text-[10px] text-gray-300 font-bold">ICON</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-800 flex items-center">
                                        @if($level > 0)
                                            <span class="text-gray-300 font-normal mr-1">{{ $prefix }}</span>
                                        @endif
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="hover:text-primary transition-colors">{{ $category->name }}</a>
                                    </div>
                                    <div class="flex space-x-3 text-[10px] mt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500 font-bold hover:underline">Edit</a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 font-bold hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('categories.show', $category) }}" target="_blank" class="text-gray-400 hover:text-gray-600">View</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400 font-mono text-[10px]">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            @if($category->parent)
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-sm text-[10px] font-bold uppercase">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-gray-300 italic text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-gray-700">{{ $category->products_count }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
