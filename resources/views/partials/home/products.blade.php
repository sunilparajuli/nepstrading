<section class="py-20 px-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-12">
        <div>
            @if($section->title)
                <h2 class="text-4xl font-bold uppercase tracking-tighter mb-2" style="font-family: 'DM Serif Display', serif;">{{ $section->title }}</h2>
            @endif
            @if($section->subtitle)
                <p class="text-gray-500">{{ $section->subtitle }}</p>
            @endif
        </div>
        <a href="/products" class="text-sm font-bold uppercase tracking-widest border-b-2 border-black pb-1">View All</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-{{ $section->data['columns'] ?? 4 }} gap-8">
        @foreach($section->resolved_data as $product)
        <div class="group flex flex-col h-full bg-white border border-transparent hover:border-gray-100 p-2 rounded-lg transition-all">
            <a href="{{ route('products.show', $product) }}" class="block mb-4 overflow-hidden bg-gray-50 aspect-[4/5] rounded-md flex-shrink-0">
                <img src="{{ $product->image ?: 'https://placehold.co/400x500' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
            </a>
            <div class="flex flex-col flex-grow">
                <div class="flex justify-between items-start gap-4 mb-2">
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-lg mb-1 truncate group-hover:text-primary transition-colors" title="{{ $product->name }}">{{ $product->name }}</h3>
                        <p class="text-gray-500 text-sm italic">{{ $product->categories->first()->name ?? 'Uncategorized' }}</p>
                    </div>
                    <p class="font-bold text-xl text-primary whitespace-nowrap">${{ number_format($product->price, 2) }}</p>
                </div>
            </div>
            
            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-auto pt-4">
                @csrf
                <button type="submit" class="w-full py-3 border border-black text-xs font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-md">
                    Add to Cart
                </button>
            </form>
        </div>
        @endforeach
    </div>
</section>
