<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-medium tracking-tight text-[#002B2B] mb-4" style="font-family: 'DM Serif Display', serif;">
                <span class="italic">{{ $section->title ?: 'Shop By Aisle' }}</span>
            </h2>
            @if($section->subtitle)
            <p class="text-gray-500 max-w-2xl mx-auto">{{ $section->subtitle }}</p>
            @endif
        </div>
        
        @php
            $cols = $section->data['columns'] ?? 4;
            $gridClass = match((int)$cols) {
                1 => 'grid-cols-1',
                2 => 'grid-cols-2',
                3 => 'grid-cols-1 md:grid-cols-3',
                4 => 'grid-cols-2 lg:grid-cols-4',
                6 => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6',
                default => 'grid-cols-2 lg:grid-cols-4',
            };
        @endphp
        <div class="grid {{ $gridClass }} gap-4 md:gap-6">
            @foreach($section->resolved_data as $category)
            <a href="{{ route('categories.show', $category) }}" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 text-center overflow-hidden h-full flex flex-col border border-gray-100">
                <div class="aspect-square bg-gray-50/50 p-6 flex flex-col items-center justify-center relative overflow-hidden">
                    <img src="{{ $category->image ? asset($category->image) : 'https://placehold.co/400x400' }}" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110" alt="{{ $category->name }}">
                </div>
                <div class="p-4 mt-auto bg-white">
                    <h3 class="font-bold text-gray-900 text-[14px] leading-snug group-hover:text-[#15803D] transition-colors uppercase tracking-wide">{{ $category->name }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
