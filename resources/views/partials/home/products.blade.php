<section class="py-12 px-6 max-w-7xl mx-auto border-t border-gray-100">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-900" style="font-family: 'DM Serif Display', serif;">{{ $section->title }}</h2>
        </div>
        <a href="/products" class="text-sm font-bold text-[#15803D] hover:opacity-80 transition-opacity flex items-center gap-1 group">
            Explore All
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <div class="relative group/swiper">
        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                @foreach($section->resolved_data as $product)
                <div class="swiper-slide h-auto">
                    <div class="group flex flex-col bg-white p-4 h-full border border-transparent shadow-[0_2px_10px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 relative rounded-xl">
                        <!-- Full card clickable link -->
                        <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-0 rounded-xl" aria-label="{{ $product->name }}"></a>
                        
                        <!-- Badges -->
                        @if($product->sale_price)
                            <div class="absolute top-4 left-4 z-10 flex flex-col gap-1 pointer-events-none">
                                <span class="bg-[#CA8A04] text-white text-[10px] font-bold px-2 py-1 rounded-sm shadow-sm uppercase tracking-wider">
                                    Sale
                                </span>
                            </div>
                        @endif

                        <!-- Image (Square 1:1) -->
                        <div class="relative aspect-square mb-4 rounded-lg overflow-hidden flex items-center justify-center p-2 bg-white pointer-events-none">
                            <img src="{{ $product->image ?: 'https://placehold.co/400x400?text=No+Image' }}" 
                                 class="w-full h-full object-contain transition-transform duration-700 ease-out group-hover:scale-105 mix-blend-multiply" 
                                 alt="{{ $product->name }}">
                        </div>

                        <!-- Product Info (Left Aligned) -->
                        <div class="flex flex-col flex-grow text-left relative z-10 pointer-events-none">
                            <h3 class="font-bold text-[15px] text-gray-900 leading-snug mb-2 line-clamp-2 transition-colors h-11 tracking-tight">
                                {{ $product->name }}
                            </h3>
                            
                            <!-- Stock status -->
                            <span class="text-[12px] mb-2 block font-medium">
                                @if($product->manage_stock && $product->stock_quantity <= 0)
                                    <span class="text-red-600 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>Out of stock</span>
                                @else
                                    <span class="text-[#15803D] flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-[#15803D]"></span>In stock</span>
                                @endif
                            </span>
                            
                            <!-- Pricing -->
                            <div class="mt-auto pt-1 pb-4 flex items-baseline flex-wrap gap-2">
                                @if($product->sale_price)
                                    <span class="text-[22px] font-black text-gray-900 tracking-tight">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-[14px] text-gray-400 line-through font-medium">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-[22px] font-black text-gray-900 tracking-tight">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Permanent Full-width Cart Button -->
                        <div class="relative z-10 mt-auto">
                            @if($product->allow_add_to_cart)
                                @if(!($product->manage_stock && $product->stock_quantity <= 0))
                                    <button type="button" onclick="addToCart({{ $product->id }})" 
                                            class="w-full py-3 bg-[hsl(var(--primary))] text-[hsl(var(--primary-fg))] text-[13px] font-bold rounded-lg hover:opacity-90 transition-colors flex items-center justify-center tracking-wide shadow-sm hover:shadow">
                                        + ADD TO CART
                                    </button>
                                @else
                                    <button type="button" disabled 
                                            class="w-full py-3 bg-gray-100 text-gray-400 text-[13px] font-bold rounded-lg cursor-not-allowed flex items-center justify-center tracking-wide">
                                        SOLD OUT
                                    </button>
                                @endif
                            @else
                                <button type="button" onclick="showCartDisabledMessage('{{ addslashes($product->cart_disabled_message) }}')" 
                                        class="w-full py-3 bg-[hsl(var(--accent))] text-[hsl(var(--accent-fg))] text-[13px] font-bold rounded-lg hover:opacity-90 transition-colors flex items-center justify-center tracking-wide shadow-sm hover:shadow">
                                    INQUIRE
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Carousel Buttons -->
        <div class="swiper-button-prev !-left-4 md:!-left-8 opacity-0 group-hover/swiper:opacity-100 transition-opacity"></div>
        <div class="swiper-button-next !-right-4 md:!-right-8 opacity-0 group-hover/swiper:opacity-100 transition-opacity"></div>
    </div>
</section>
