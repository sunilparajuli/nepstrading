<section class="py-24 px-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-baseline mb-16 gap-4">
        <div>
            @if($section->title)
                <h2 class="text-4xl md:text-5xl font-medium tracking-tighter mb-4" style="font-family: 'DM Serif Display', serif;">{{ $section->title }}</h2>
            @endif
            @if($section->subtitle)
                <p class="text-muted-fg max-w-xl leading-relaxed">{{ $section->subtitle }}</p>
            @endif
        </div>
        <a href="/products" class="group flex items-center text-xs font-bold uppercase tracking-[0.2em] text-fg hover:text-primary transition-colors">
            Explore All 
            <svg class="ml-2 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-{{ $section->data['columns'] ?? 4 }} gap-x-6 gap-y-12">
        @foreach($section->resolved_data as $product)
        <div class="group flex flex-col h-full bg-transparent transition-all">
            <div class="relative mb-6 overflow-hidden rounded-sm aspect-[4/5] bg-muted">
                <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                    <img src="{{ $product->image ?: 'https://placehold.co/400x500' }}" class="w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-105" alt="{{ $product->name }}">
                </a>
                
                @if($product->sale_price)
                    <span class="absolute top-4 left-4 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full">
                        Sale
                    </span>
                @endif

                <div class="absolute inset-x-4 bottom-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-white text-black text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all shadow-lg rounded-sm">
                            Quick Add
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex flex-col flex-grow text-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-muted-fg mb-2">{{ $product->categories->first()->name ?? 'Collection' }}</p>
                <h3 class="font-medium text-base mb-2 group-hover:text-primary transition-colors">
                    <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                </h3>
                <div class="flex items-center justify-center gap-2">
                    @if($product->sale_price)
                        <span class="text-sm font-bold text-sale">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-xs text-muted-fg line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-sm font-bold text-fg">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
