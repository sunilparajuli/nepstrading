<section class="py-24 bg-[#FAFAFA]">
    <div class="max-w-7xl mx-auto px-8">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-medium tracking-tighter mb-6" style="font-family: 'DM Serif Display', serif;">
                {{ $section->title ?: 'Shop By Collection' }}
            </h2>
            <p class="text-muted-fg max-w-2xl mx-auto leading-relaxed">{{ $section->subtitle }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($section->resolved_data as $category)
            <a href="{{ route('categories.show', $category) }}" class="group relative aspect-square overflow-hidden rounded-md bg-muted shadow-sm hover:shadow-xl transition-all duration-500">
                <img src="{{ $category->image ? asset($category->image) : 'https://placehold.co/600x600' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="{{ $category->name }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="absolute inset-x-0 bottom-0 p-8 text-white">
                    <h3 class="text-2xl font-medium tracking-tight mb-3" style="font-family: 'DM Serif Display', serif;">{{ $category->name }}</h3>
                    <div class="flex items-center text-[10px] font-bold uppercase tracking-[0.2em] opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        Explore Collection
                        <svg class="ml-2 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
