<section class="relative h-[400px] md:h-[500px] overflow-hidden group">
    <img src="{{ asset('assets/banners/pooja_banner.png') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Nepalese Incense & Pooja Items">
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
    
    <div class="relative max-w-7xl mx-auto h-full px-6 flex flex-col justify-center">
        <div class="max-w-xl">
            <span class="inline-block bg-[#fcb800] text-black text-[10px] font-black uppercase tracking-widest px-3 py-1 mb-4">
                Ritual Essentials
            </span>
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-4 leading-tight font-serif">
                Nepalese Incense <br>& Pooja Items
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-md font-medium leading-relaxed">
                Authentic ritual supplies for your spiritual needs. 
                <span class="block mt-2 text-[#fcb800] font-bold italic text-sm">"Store is also upstairs"</span>
            </p>
            
            <div class="flex flex-wrap gap-4 items-center">
                <a href="{{ route('products.index', ['category' => 'incense-pooja']) }}" class="bg-white text-black px-8 py-4 rounded-sm text-xs font-black uppercase tracking-widest hover:bg-[#fcb800] transition-colors shadow-xl">
                    Explore Collection
                </a>
                <div class="text-white/80 text-[10px] font-black uppercase tracking-widest border-l-2 border-[#fcb800] pl-4">
                    WHOLESALE / RETAIL / <br>RESTAURANT SUPPLY
                </div>
            </div>
        </div>
    </div>
</section>
