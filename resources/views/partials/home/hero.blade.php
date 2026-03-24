<section class="relative h-[600px] flex items-center justify-center overflow-hidden bg-gray-900">
    @if($section->data['bg_image'] ?? '')
        <img src="{{ $section->data['bg_image'] }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="">
    @endif
    
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 uppercase tracking-tighter" style="font-family: 'DM Serif Display', serif;">
            {{ $section->title }}
        </h1>
        <p class="text-xl text-gray-200 mb-10 max-w-2xl mx-auto">
            {{ $section->subtitle }}
        </p>
        <a href="{{ $section->data['button_link'] ?? '/products' }}" class="inline-block px-10 py-4 bg-white text-black font-bold uppercase tracking-widest hover:bg-gray-200 transition-colors">
            {{ $section->data['button_text'] ?? 'Shop Now' }}
        </a>
    </div>
</section>
