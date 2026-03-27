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

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-{{ $section->data['columns'] ?? 5 }} gap-4">
        @foreach($section->resolved_data as $product)
        <div class="group flex flex-col bg-white p-4 h-full border border-gray-100 hover:border-transparent hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 relative rounded-lg">
            <!-- Badges -->
            @if($product->sale_price)
                <div class="absolute top-4 left-4 z-10 flex flex-col gap-1">
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-sm shadow-sm uppercase tracking-tighter">
                        ${{ number_format($product->price - $product->sale_price, 2) }} OFF
                    </span>
                </div>
            @endif

            <!-- Image (Square 1:1) -->
            <div class="relative aspect-square mb-4 rounded-lg overflow-hidden flex items-center justify-center p-2">
                <a href="{{ route('products.show', $product) }}" class="block w-full h-full p-6">
                    <img src="{{ $product->image ?: 'https://placehold.co/400x400?text=No+Image' }}" 
                         class="w-full h-full object-contain transition-transform duration-700 ease-out group-hover:scale-110" 
                         alt="{{ $product->name }}">
                </a>
            </div>

            <!-- Product Info (Left Aligned) -->
            <div class="flex flex-col flex-grow text-left">
                <h3 class="font-semibold text-[14px] text-gray-800 leading-snug mb-2 line-clamp-2 hover:text-[#1F8A43] transition-colors h-10">
                    <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                </h3>
                
                <!-- Stock status -->
                <span class="text-[12px] text-gray-500 mb-2 block">
                    @if($product->manage_stock && $product->stock_quantity <= 0)
                        <span class="text-red-500 font-medium">Out of stock</span>
                    @else
                        <span class="text-[#1F8A43] font-medium">In stock</span>
                    @endif
                </span>
                
                <!-- Pricing -->
                <div class="mt-auto pt-1 pb-4 flex items-center flex-wrap gap-2">
                    @if($product->sale_price)
                        <span class="text-[20px] font-bold text-[#E5222E]">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-[13px] text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                        <span class="text-[20px] font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Permanent Full-width Cart Button -->
                @if(!($product->manage_stock && $product->stock_quantity <= 0))
                    <button type="button" onclick="addToCart({{ $product->id }})" 
                            class="w-full py-2.5 mt-auto bg-[#1F8A43] text-white text-[13px] font-bold rounded hover:bg-[#176d34] transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Add to Cart
                    </button>
                @else
                    <button type="button" disabled 
                            class="w-full py-2.5 mt-auto bg-gray-200 text-gray-500 text-[13px] font-bold rounded cursor-not-allowed flex items-center justify-center gap-2">
                        Sold Out
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
