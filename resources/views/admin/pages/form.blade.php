@extends('layouts.admin')

@section('title', $page->exists ? 'Edit Page' : 'Add New Page')

@section('content')
<div class="max-w-4xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">{{ $page->exists ? 'Edit Page' : 'Add New Page' }}</h2>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST">
        @csrf
        @if($page->exists) @method('PUT') @endif

        <div class="bg-white rounded-sm shadow-sm border border-gray-100 p-6 space-y-6">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Page Title</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" 
                       class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none"
                       placeholder="Enter page title" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" 
                       class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none font-mono"
                       placeholder="auto-generated-from-title">
                <p class="text-[10px] text-gray-400 mt-1 italic">Leave empty to auto-generate from title.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Content</label>
                <textarea name="content" rows="12" 
                          class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none"
                          placeholder="Write your page content here...">{{ old('content', $page->content) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                        <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Footer Section</label>
                    <select name="footer_section" class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50">
                        <option value="" {{ old('footer_section', $page->footer_section) == '' ? 'selected' : '' }}>Hide from Footer</option>
                        <option value="Quick Links" {{ old('footer_section', $page->footer_section) == 'Quick Links' ? 'selected' : '' }}>Quick Links</option>
                        <option value="Customer Service" {{ old('footer_section', $page->footer_section) == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" 
                           class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none"
                           placeholder="0">
                </div>
                <div class="flex items-center pt-6">
                    <input type="hidden" name="show_on_footer" value="0">
                    <input type="checkbox" name="show_on_footer" value="1" id="show_on_footer" {{ old('show_on_footer', $page->show_on_footer ?? true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="show_on_footer" class="ml-2 block text-sm text-gray-700">Show in Footer</label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" 
                           class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none"
                           placeholder="SEO title (optional)">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Meta Description</label>
                <textarea name="meta_description" rows="2" 
                          class="w-full border border-gray-200 rounded-sm text-sm p-3 bg-gray-50 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 outline-none"
                          placeholder="SEO description (optional)">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded text-sm font-semibold hover:bg-blue-700 transition">
                    {{ $page->exists ? 'Update Page' : 'Publish Page' }}
                </button>
                <a href="{{ route('admin.pages.index') }}" class="text-gray-500 text-sm hover:text-gray-700">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
