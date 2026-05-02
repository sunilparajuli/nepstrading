@php
    $showcaseCategories = \App\Models\Category::whereNull('parent_id')
        ->withCount('products')
        ->orderBy('name')
        ->limit(5)
        ->get();
@endphp

@if($showcaseCategories->count() >= 5)
<section class="py-20 bg-[#F8FAFC] overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
            
            <!-- Left Side: Content -->
            <div class="lg:w-2/5 text-center lg:text-left">
                <div class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest mb-6">
                    Discover More
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-[#1e3a8a] leading-tight mb-6">
                    Browse by <br>
                    <span class="relative">
                        Categories
                        <svg class="absolute -bottom-4 left-0 w-full" height="10" viewBox="0 0 100 10" preserveAspectRatio="none">
                            <path d="M0 5 Q 25 0 50 5 T 100 5" fill="none" stroke="#fcb800" stroke-width="4" />
                        </svg>
                    </span>
                </h2>
                <p class="text-gray-500 text-lg mb-10 max-w-md mx-auto lg:mx-0 leading-relaxed">
                    Explore our wide range of premium products, from daily essentials to exotic spices and authentic Pooja items.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 bg-[#1e3a8a] text-white px-8 py-4 rounded-xl font-bold hover:bg-[#fcb800] hover:text-[#1e3a8a] transition-all duration-300 group">
                    View All Categories
                    <svg class="group-hover:translate-x-1 transition-transform" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Right Side: Staggered Grid -->
            <div class="lg:w-3/5 w-full">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                    {{-- Card 1: Top Left (Span 3) --}}
                    <div class="md:col-span-3">
                        @include('partials.home.category-card-modern', ['category' => $showcaseCategories[0]])
                    </div>
                    {{-- Card 2: Top Right (Span 3) --}}
                    <div class="md:col-span-3 mt-0 md:mt-8">
                        @include('partials.home.category-card-modern', ['category' => $showcaseCategories[1]])
                    </div>
                    {{-- Card 3: Bottom Left (Span 2) --}}
                    <div class="md:col-span-2">
                        @include('partials.home.category-card-modern', ['category' => $showcaseCategories[2]])
                    </div>
                    {{-- Card 4: Bottom Middle (Span 2) --}}
                    <div class="md:col-span-2 mt-0 md:mt-8">
                        @include('partials.home.category-card-modern', ['category' => $showcaseCategories[3]])
                    </div>
                    {{-- Card 5: Bottom Right (Span 2) --}}
                    <div class="md:col-span-2">
                        @include('partials.home.category-card-modern', ['category' => $showcaseCategories[4]])
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .cat-modern-card {
        background: white;
        padding: 40px 30px;
        border-radius: 24px;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 10px 30px -15px rgba(0,0,0,0.05);
        display: block;
        text-decoration: none;
        height: 100%;
    }
    
    .cat-modern-card:hover {
        background: #1e3a8a;
        transform: translateY(-10px);
        box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.3);
    }
    
    .cat-icon-wrap {
        width: 70px;
        height: 70px;
        background: #F1F5F9;
        border-radius: 50%;
        display: flex;
        items-center: center;
        justify-content: center;
        margin: 0 auto 24px;
        transition: all 0.3s ease;
    }
    
    .cat-modern-card:hover .cat-icon-wrap {
        background: rgba(255,255,255,0.1);
        transform: scale(1.1);
    }
    
    .cat-modern-card h3 {
        font-weight: 800;
        font-size: 18px;
        color: #1e3a8a;
        margin-bottom: 8px;
        transition: color 0.3s ease;
    }
    
    .cat-modern-card p {
        font-size: 13px;
        color: #64748b;
        transition: color 0.3s ease;
    }
    
    .cat-modern-card:hover h3,
    .cat-modern-card:hover p {
        color: white;
    }
</style>
@endif
