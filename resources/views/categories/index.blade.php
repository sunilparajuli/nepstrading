@extends('layouts.app')

@section('title', 'Categories - Nepstrading')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-serif text-gray-900 mb-4">Our Categories</h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto">Explore our wide range of fresh produce, pantry staples, and household essentials.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($categories as $category)
            <a href="{{ route('categories.show', $category) }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-500">
                <div class="aspect-[4/3] overflow-hidden bg-gray-100">
                    <img src="{{ $category->image ?: 'https://placehold.co/800x600?text=' . urlencode($category->name) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $category->name }}">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <h3 class="text-2xl font-bold mb-2">{{ $category->name }}</h3>
                    <div class="flex items-center gap-4 text-sm text-gray-200">
                        <span>{{ $category->children->count() }} Subcategories</span>
                        <span class="w-1 h-1 rounded-full bg-gray-400"></span>
                        <span>View All <svg xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
