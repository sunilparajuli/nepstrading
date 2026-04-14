@extends('layouts.app')

@section('meta_title', 'Nepstrading - Authentic Indian & Nepali Groceries Delivery')
@section('meta_description', 'Your one-stop shop for authentic Indian and Nepali groceries, spices, snacks, and premium pantry essentials. Fast delivery across Australia.')
@section('meta_keywords', 'indian groceries, nepali spices, authentic nepalese, snacks, pantry essentials, delivery, Nepstrading Australia')

@push('seo_schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "Nepstrading",
  "url": "{{ url('/') }}",
  "logo": "{{ asset(\App\Models\SiteSetting::getValue('logo') ?: 'images/logo.png') }}",
  "contactPoint": {
    "@@type": "ContactPoint",
    "telephone": "{{ \App\Models\SiteSetting::getValue('footer_phone', '+61 000 000 000') }}",
    "contactType": "customer service",
    "areaServed": "AU",
    "availableLanguage": "en"
  },
  "sameAs": [
    "{{ \App\Models\SiteSetting::getValue('footer_facebook_url', '#') }}",
    "{{ \App\Models\SiteSetting::getValue('footer_instagram_url', '#') }}",
    "{{ \App\Models\SiteSetting::getValue('footer_youtube_url', '#') }}"
  ]
}
</script>
@endpush

@section('content')
    <div class="homepage-content">
        @include('partials.home.popular-categories')
        @forelse ($sections as $section)
            @switch($section->type)
                @case('hero')
                    @include('partials.home.hero', ['section' => $section])
                    @break
                @case('featured_products')
                @case('popular_products')
                @case('new_products')
                @case('weekly_special')
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
