<section class="s-popularCategories py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-10 text-center font-serif">Popular Categorys</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {{-- Row 1, Col 1 --}}
            <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all hover:shadow-md">
                <a href="{{ route('products.index', ['category' => 'ndh-masala-range']) }}" class="block p-4">
                    <img src="{{ asset('assets/banners/ndh_masala.jpg') }}" alt="NDH Masala Range" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            </div>

            {{-- Row 1, Col 2 --}}
            <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all hover:shadow-md">
                <a href="{{ route('products.index', ['category' => 'rice']) }}" class="block p-4">
                    <img src="{{ asset('assets/banners/rice_top.jpg') }}" alt="Rice Selection" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            </div>

            {{-- Row 1, Col 3 --}}
            <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all hover:shadow-md">
                <a href="{{ route('products.index', ['category' => 'nepali-lentils']) }}" class="block p-4">
                    <img src="{{ asset('assets/banners/lentils.jpg') }}" alt="Nepali Lentils" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            </div>

            {{-- Row 2, Col 1 --}}
            <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all hover:shadow-md">
                <a href="{{ route('products.index', ['category' => 'authentic-nepali-spices']) }}" class="block p-4">
                    <img src="{{ asset('assets/banners/spices.jpg') }}" alt="Authentic Nepali Spices" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            </div>

            {{-- Row 2, Col 2 --}}
            <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all hover:shadow-md">
                <a href="{{ route('products.index') }}" class="block p-4">
                    <img src="{{ asset('assets/banners/all_products.jpg') }}" alt="All Products" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            </div>

            {{-- Row 2, Col 3 --}}
            <div class="group relative overflow-hidden rounded-xl bg-gray-100 shadow-sm transition-all hover:shadow-md">
                <a href="{{ route('products.index', ['category' => 'rice']) }}" class="block p-4">
                    <img src="{{ asset('assets/banners/rice_top.jpg') }}" alt="Premium Rice" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[hsl(var(--primary))] hover:opacity-90 transition-opacity">
                Shop Now
                <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
