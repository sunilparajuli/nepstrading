@extends('layouts.app')

@section('title', 'Welcome to Nepstrading')

@section('content')
    <div class="homepage-content">
        @forelse ($sections as $section)
            @switch($section->type)
                @case('hero')
                    @include('partials.home.hero', ['section' => $section])
                    @break
                @case('featured_products')
                @case('popular_products')
                @case('new_products')
                    @include('partials.home.products', ['section' => $section])
                    @break
                @case('categories')
                    @include('partials.home.categories', ['section' => $section])
                    @break
            @endswitch
        @empty
            <div class="py-20 text-center">
                <h1 class="text-4xl font-bold mb-4">Welcome to Nepstrading</h1>
                <p class="text-gray-500">Visit the admin panel to customize your homepage layout.</p>
            </div>
        @endforelse
    </div>
@endsection
