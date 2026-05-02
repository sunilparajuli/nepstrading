@php
    // Assign a pastel background color based on category ID or name to keep it consistent but varied
    $bgColors = ['bg-[#E8F5E9]', 'bg-[#F3E5F5]', 'bg-[#FFF3E0]', 'bg-[#E3F2FD]', 'bg-[#FCE4EC]'];
    $bgColor = $bgColors[($product->category_id ?? 0) % count($bgColors)];
@endphp

<div class="group flex flex-col bg-white h-full transition-all duration-500 relative rounded-[2rem] overflow-hidden p-3">
    <!-- Full card clickable link -->
    <a href="{{ route('products.show', $product) }}" class="absolute inset-0 z-10" aria-label="{{ $product->name }}"></a>
    
    <!-- Image Section with Arc Background -->
    <div class="relative aspect-[4/5] mb-6 rounded-[1.5rem] overflow-hidden flex items-end justify-center group-hover:shadow-xl transition-shadow duration-500">
        {{-- The Arc Background --}}
        <div class="absolute inset-x-0 bottom-0 top-1/4 {{ $bgColor }} rounded-t-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
        
        {{-- Sale Badge --}}
        @if($product->sale_price)
            <div class="absolute top-4 left-4 z-20">
                <span class="bg-white/90 backdrop-blur-sm text-[#1e3a8a] text-[10px] font-black px-3 py-1.5 rounded-full shadow-sm uppercase tracking-widest">
                    Sale
                </span>
            </div>
        @endif

        {{-- Product Image --}}
        <img src="{{ $product->image ?: 'https://placehold.co/400x500?text=No+Image' }}" 
             class="relative z-10 w-[85%] h-[85%] object-contain transition-transform duration-700 ease-out group-hover:scale-110 mb-4" 
             alt="{{ $product->name }}">
    </div>

    <!-- Info Section -->
    <div class="px-2 pb-2 relative z-20 flex justify-between items-end">
        <div class="flex-1">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-widest block mb-1">
                {{ $product->category->name ?? 'Premium Item' }}
            </span>
            <h3 class="font-black text-[17px] text-[#1e3a8a] leading-tight mb-2 tracking-tight group-hover:text-[#fcb800] transition-colors duration-300">
                {{ $product->name }}
            </h3>
            <div class="flex items-baseline gap-2">
                @if($product->sale_price)
                    <span class="text-[18px] font-black text-[#1e3a8a]">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="text-[13px] text-gray-400 line-through font-bold">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="text-[18px] font-black text-[#1e3a8a]">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>

        {{-- Circular Action Button --}}
        <div class="relative z-30">
            @if($product->allow_add_to_cart && !($product->manage_stock && $product->stock_quantity <= 0))
                <button type="button" onclick="addToCart({{ $product->id }})" 
                        class="w-12 h-12 bg-blue-50 text-[#1e3a8a] rounded-full flex items-center justify-center hover:bg-[#1e3a8a] hover:text-white transition-all duration-300 shadow-sm border border-blue-100 group/btn">
                    <svg class="group-hover/btn:rotate-90 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                </button>
            @elseif(!$product->allow_add_to_cart)
                <button type="button" onclick="showCartDisabledMessage('{{ addslashes($product->cart_disabled_message) }}')" 
                        class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all duration-300 shadow-sm border border-orange-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16h.01"/><path d="M12 8v4"/></svg>
                </button>
            @else
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center border border-gray-100 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m2 2 20 20"/><path d="M8.35 8.35 4 12.65a2 2 0 0 0 0 2.83L6.17 17.65a2 2 0 0 0 2.83 0L12 14.65"/><path d="M11 2h2"/><path d="m15.65 15.65 4.35-4.35a2 2 0 0 0 0-2.83L17.83 6.35a2 2 0 0 0-2.83 0L12 9.35"/><path d="M12 22v-2"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m19.07 4.93-1.41 1.41"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 19.07-1.41-1.41"/><path d="m6.34 6.34-1.41-1.41"/></svg>
                </div>
            @endif
        </div>
    </div>
</div>
