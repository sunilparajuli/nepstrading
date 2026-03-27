<section class="py-12 px-6 max-w-7xl mx-auto bg-white border-t border-gray-50">
    <div class="flex items-center justify-between mb-10 pb-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-primary rounded-full"></div>
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-fg">{{ $section->title }}</h2>
        </div>
        <a href="/products" class="text-sm font-bold text-primary hover:opacity-80 transition-opacity flex items-center gap-1 group">
            Explore All
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-{{ $section->data['columns'] ?? 5 }} gap-px bg-gray-100 border border-gray-100 rounded-xl overflow-hidden shadow-sm">
        @foreach($section->resolved_data as $product)
        <div class="group flex flex-col bg-white p-4 h-full transition-all hover:z-10 hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] relative">
            <!-- Badges -->
            @if($product->sale_price)
                <div class="absolute top-4 left-4 z-10 flex flex-col gap-1">
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-sm shadow-sm uppercase tracking-tighter">
                        ${{ number_format($product->price - $product->sale_price, 2) }} OFF
                    </span>
                </div>
            @endif

            <!-- Image (Square 1:1) -->
            <div class="relative aspect-square mb-5 bg-gray-50/50 rounded-lg overflow-hidden flex items-center justify-center group-hover:bg-white transition-colors duration-500">
                <a href="{{ route('products.show', $product) }}" class="block w-full h-full p-6">
                    <img src="{{ $product->image ?: 'https://placehold.co/400x400?text=No+Image' }}" 
                         class="w-full h-full object-contain transition-transform duration-700 ease-out group-hover:scale-110" 
                         alt="{{ $product->name }}">
                </a>
            </div>

            <!-- Product Info (Left Aligned) -->
            <div class="flex flex-col flex-grow text-left">
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted-fg mb-1.5">{{ $product->categories->first()->name ?? 'Store' }}</span>
                <h3 class="font-bold text-[14px] leading-tight text-fg mb-3 h-10 line-clamp-2 hover:text-primary transition-colors duration-200">
                    <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                </h3>
                
                <!-- Pricing -->
                <div class="mt-auto pt-2 pb-5 flex items-baseline gap-2">
                    @if($product->sale_price)
                        <div class="flex flex-col">
                            <span class="text-lg font-black text-red-600 leading-none">
                                ${{ floor($product->sale_price) }}<span class="text-xs align-top mt-1">.{{ substr(number_format($product->sale_price, 2), -2) }}</span>
                            </span>
                            <span class="text-[11px] text-muted-fg line-through font-semibold mt-0.5 italic">WAS ${{ number_format($product->price, 2) }}</span>
                        </div>
                    @else
                        <span class="text-lg font-black text-fg leading-none">
                            ${{ floor($product->price) }}<span class="text-xs align-top mt-1">.{{ substr(number_format($product->price, 2), -2) }}</span>
                        </span>
                    @endif
                </div>

                <!-- Permanent Full-width Cart Button -->
                <button type="button" onclick="addToCart({{ $product->id }})" 
                        class="w-full py-3 bg-primary text-primary-fg text-[11px] font-black uppercase tracking-widest rounded-lg shadow-md hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2 border-b-4 border-black/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>
        </div>
        @endforeach
    </div>
</section>
