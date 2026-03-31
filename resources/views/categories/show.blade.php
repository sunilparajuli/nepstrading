@extends('layouts.app')

@section('meta_title', $category->meta_title ?: $category->name . ' - ' . config('app.name'))
@section('meta_description', $category->meta_description ?: 'Explore our selection of ' . $category->name . ' at ' . config('app.name') . '.')
@section('meta_keywords', $category->meta_keywords ?: $category->name . ', grocery, shopping')
@section('og_type', 'website')
@section('og_title', $category->og_title ?: $category->meta_title ?: $category->name)
@section('og_description', $category->og_description ?: $category->meta_description)
@section('og_image', $category->og_image ? asset($category->og_image) : ($category->image ? asset($category->image) : asset('images/og-default.jpg')))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ url('/') }}" class="hover:text-primary transition-colors">Home</a>
            </li>
            @if($category->parent)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('categories.show', $category->parent) }}" class="ml-1 md:ml-2 hover:text-primary transition-colors">{{ $category->parent->name }}</a>
                    </div>
                </li>
            @endif
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 font-medium text-gray-900">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-12">
        <!-- Sidebar Filters -->
        <div class="w-full md:w-64 flex-shrink-0">
            @if($category->children->count() > 0)
                <div class="mb-10">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 mb-4">Subcategories</h3>
                    <ul class="space-y-2">
                        @foreach($category->children as $sub)
                            <li>
                                <a href="{{ route('categories.show', $sub) }}" class="text-gray-600 hover:text-primary transition-colors flex justify-between items-center group">
                                    {{ $sub->name }}
                                    <span class="text-xs text-gray-400 group-hover:text-primary">({{ $sub->products()->count() }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-10">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 mb-4">Related Categories</h3>
                <ul class="space-y-2">
                    @foreach(\App\Models\Category::whereNull('parent_id')->where('id', '!=', $category->id)->where('id', '!=', $category->parent_id)->limit(5)->get() as $rel)
                        <li>
                            <a href="{{ route('categories.show', $rel) }}" class="text-gray-600 hover:text-primary transition-colors">{{ $rel->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <div class="flex justify-between items-end mb-8 border-b border-gray-100 pb-6">
                <div>
                    <h1 class="text-4xl font-serif text-gray-900 mb-2">{{ $category->name }}</h1>
                    <p class="text-gray-500">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products</p>
                </div>
                <div class="flex items-center gap-4">
                    <select class="border-gray-200 rounded-lg text-sm focus:ring-primary focus:border-primary">
                        <option>Sort by: Latest</option>
                        <option>Sort by: Price (Low to High)</option>
                        <option>Sort by: Price (High to Low)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $product)
                    <div class="group">
                        <div class="relative aspect-square overflow-hidden bg-gray-50 rounded-2xl mb-4 text-center flex items-center justify-center">
                            <img src="{{ $product->image ?: 'https://placehold.co/400x400' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors"></div>
                            
                            <div class="absolute bottom-4 left-4 right-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                @if($product->allow_add_to_cart)
                                    <button type="button" onclick="addToCart({{ $product->id }})" class="w-full bg-[hsl(var(--primary))] text-[hsl(var(--primary-fg))] font-bold py-3 rounded-xl shadow-lg hover:opacity-90 transition-colors flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        Add to Cart
                                    </button>
                                @else
                                    <button type="button" onclick="showCartDisabledMessage('{{ addslashes($product->cart_disabled_message) }}')" 
                                            class="w-full bg-[hsl(var(--accent))] text-[hsl(var(--accent-fg))] font-bold py-3 rounded-xl shadow-lg hover:opacity-90 transition-colors flex items-center justify-center gap-2">
                                        Inquiry
                                    </button>
                                @endif
                            </div>
                        </div>
                        <h3 class="font-bold text-gray-900 group-hover:text-[hsl(var(--primary))] transition-colors mb-1">
                            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                        </h3>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500">We couldn't find any products in this category at the moment.</p>
                        <a href="{{ route('products.index') }}" class="mt-8 inline-block bg-[hsl(var(--primary))] text-[hsl(var(--primary-fg))] px-8 py-3 rounded-xl font-bold hover:opacity-90 transition-opacity">Browse All Products</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
