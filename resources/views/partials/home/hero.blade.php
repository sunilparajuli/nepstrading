<section class="relative bg-white border-b border-gray-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-12 md:py-20 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="w-full md:w-1/2 z-10">
            <span class="inline-block text-sm font-bold uppercase tracking-[0.2em] text-[#15803D] mb-4">
                Fresh & Premium
            </span>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-medium text-gray-900 mb-6 leading-[1.05] tracking-tight" style="font-family: 'DM Serif Display', serif;">
                {{ $section->title }}
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-lg leading-relaxed">
                {{ $section->subtitle }}
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ $section->data['button_link'] ?? '/products' }}" class="inline-flex items-center justify-center px-8 py-4 rounded-md bg-[hsl(var(--primary))] text-[hsl(var(--primary-fg))] text-sm font-bold transition-colors hover:opacity-90 shadow-sm">
                    {{ $section->data['button_text'] ?? 'Shop Now' }}
                </a>
            </div>
        </div>
        <div class="w-full md:w-1/2 z-10 relative">
            @if($section->data['bg_image'] ?? '')
                <!-- Floating decorative element -->
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-[#002B2B] rounded-full opacity-10"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#15803D] rounded-full opacity-10"></div>
                
                <div class="rounded-2xl overflow-hidden shadow-2xl relative aspect-[4/3] bg-white border border-gray-100 p-2">
                    <img src="{{ $section->data['bg_image'] }}" class="w-full h-full object-cover rounded-xl" alt="Promotional Image">
                </div>
            @endif
        </div>
    </div>
</section>
