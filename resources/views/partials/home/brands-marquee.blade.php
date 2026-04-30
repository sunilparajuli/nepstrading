@php
    $brands = \App\Models\Brand::where('is_enabled', true)->orderBy('sort_order')->get();
@endphp

@if($brands->count() > 0)
<section class="py-12 bg-white border-t border-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 mb-8 text-center">
        <h3 class="text-xs font-black uppercase tracking-[0.3em] text-gray-400">Our Trusted Brands</h3>
    </div>
    
    <div class="relative">
        <div class="flex marquee-track">
            {{-- Double the brands for infinite scroll effect --}}
            @foreach($brands->concat($brands) as $brand)
                <div class="flex-shrink-0 px-12 grayscale hover:grayscale-0 transition-all duration-300 flex items-center justify-center">
                    <img src="{{ $brand->logo }}" alt="{{ $brand->name }}" class="h-10 md:h-12 w-auto object-contain opacity-70 hover:opacity-100">
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .marquee-track {
        display: flex;
        width: max-content;
        animation: marquee-scroll 40s linear infinite;
    }
    
    @keyframes marquee-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .marquee-track:hover {
        animation-play-state: paused;
    }
</style>
@endif
