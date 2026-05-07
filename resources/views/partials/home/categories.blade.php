<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-medium tracking-tight text-[#002B2B] mb-4 hp-title-anim" style="font-family: 'DM Serif Display', serif;">
                <span class="italic">{{ $section->title ?: 'Shop By Aisle' }}</span>
            </h2>
            @if($section->subtitle)
            <p class="text-gray-500 max-w-2xl mx-auto hp-subtitle-anim">{{ $section->subtitle }}</p>
            @endif
        </div>
        
        @php
            $cols = $section->data['columns'] ?? 6;
        @endphp
        <div class="relative group/swiper px-2 md:px-8">
            <div class="swiper product-swiper category-swiper" data-cols="{{ $cols }}">
                <div class="swiper-wrapper py-4">
                    @foreach($section->resolved_data as $category)
                    <div class="swiper-slide h-auto">
                        <a href="{{ route('categories.show', $category) }}" class="group flex flex-col items-center justify-start text-center h-full w-full">
                            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-36 md:h-36 mb-4 rounded-full border-4 border-white shadow-[0_4px_20px_rgba(0,0,0,0.08)] group-hover:shadow-[0_8px_25px_rgba(21,128,61,0.2)] group-hover:border-[#15803D]/10 overflow-hidden relative transition-all duration-300 bg-gray-50 flex-shrink-0">
                                @php
                                    $imgUrl = $category->image 
                                        ? (Str::startsWith($category->image, 'http') ? $category->image : asset('storage/' . $category->image)) 
                                        : 'https://placehold.co/400x400';
                                @endphp
                                <img src="{{ $imgUrl }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="{{ $category->name }}">
                            </div>
                            <h3 class="font-bold text-gray-900 text-[13px] md:text-[15px] leading-tight group-hover:text-[#15803D] transition-colors line-clamp-2 px-1 max-w-[140px]">{{ $category->name }}</h3>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="swiper-button-prev !-left-2 md:!-left-4 md:opacity-0 group-hover/swiper:opacity-100 transition-opacity"></div>
            <div class="swiper-button-next !-right-2 md:!-right-4 md:opacity-0 group-hover/swiper:opacity-100 transition-opacity"></div>
        </div>
    </div>
</section>
