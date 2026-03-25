<section class="relative h-[80vh] min-h-[600px] flex items-center overflow-hidden bg-black">
    @if($section->data['bg_image'] ?? '')
        <div class="absolute inset-0 z-0">
            <img src="{{ $section->data['bg_image'] }}" class="w-full h-full object-cover" alt="">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
        </div>
    @endif
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-8 md:px-12">
        <div class="max-w-2xl">
            <span class="inline-block text-xs font-bold uppercase tracking-[0.3em] text-accent mb-6 animate-fade-in">
                Premium Selection
            </span>
            <h1 class="text-6xl md:text-8xl font-medium text-white mb-8 leading-[0.9] tracking-tighter" style="font-family: 'DM Serif Display', serif;">
                {{ $section->title }}
            </h1>
            <p class="text-lg md:text-xl text-white/70 mb-12 max-w-lg leading-relaxed font-light">
                {{ $section->subtitle }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ $section->data['button_link'] ?? '/products' }}" class="inline-flex items-center px-8 py-4 bg-white text-black text-sm font-bold uppercase tracking-widest hover:bg-accent hover:text-white transition-all duration-300 rounded-sm">
                    {{ $section->data['button_text'] ?? 'Shop Now' }}
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</section>
