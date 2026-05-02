<section class="py-12 px-6 max-w-7xl mx-auto border-t border-gray-100">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-900" style="font-family: 'DM Serif Display', serif;">{{ $section->title }}</h2>
        </div>
        @php
            $exploreUrl = match($section->type) {
                'new_products' => route('products.index', ['sort' => 'latest']),
                'popular_products' => route('products.index', ['popular' => 1]),
                'featured_products' => route('products.index', ['seasonal' => 1]),
                'weekly_special' => route('products.index', ['stock' => 'in']),
                default => route('products.index'),
            };
        @endphp
        <a href="{{ $exploreUrl }}" class="text-sm font-bold text-[#15803D] hover:opacity-80 transition-opacity flex items-center gap-1 group">
            Explore All
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <div class="relative group/swiper">
        <div class="swiper product-swiper" data-cols="{{ $section->data['columns'] ?? 4 }}">
            <div class="swiper-wrapper">
                @foreach($section->resolved_data as $product)
                <div class="swiper-slide h-auto">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </div>

        <!-- Carousel Buttons -->
        <div class="swiper-button-prev !-left-4 md:!-left-8 opacity-0 group-hover/swiper:opacity-100 transition-opacity"></div>
        <div class="swiper-button-next !-right-4 md:!-right-8 opacity-0 group-hover/swiper:opacity-100 transition-opacity"></div>
    </div>
</section>
