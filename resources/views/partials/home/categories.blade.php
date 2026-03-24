<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold uppercase tracking-tighter mb-4" style="font-family: 'DM Serif Display', serif;">{{ $section->title ?: 'Shop By Category' }}</h2>
            <p class="text-gray-500">{{ $section->subtitle }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($section->resolved_data as $category)
            <a href="{{ route('categories.show', $category) }}" class="relative h-[400px] overflow-hidden group border border-gray-200">
                <img src="{{ $category->image ? asset($category->image) : 'https://placehold.co/600x400' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                <div class="absolute inset-x-0 bottom-0 p-8 text-white">
                    <h3 class="text-2xl font-bold uppercase tracking-tight mb-2">{{ $category->name }}</h3>
                    <span class="text-xs font-bold uppercase tracking-widest border-b border-white pb-1">Explore</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
