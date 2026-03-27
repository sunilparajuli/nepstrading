@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<style>
    .sp-header { padding: 48px 0; background: hsl(var(--muted)); text-align: center; }
    .sp-headerTitle { font-family: 'DM Serif Display', serif; font-size: 36px; color: hsl(var(--fg)); margin: 0 0 8px; }
    .sp-headerDesc { font-size: 16px; color: hsl(var(--muted-fg)); margin: 0; }

    .sp-wrap { max-width: 1200px; margin: 0 auto; padding: 32px 16px; display: flex; gap: 32px; align-items: flex-start; }
    @media (max-width: 1024px) { .sp-wrap { flex-direction: column; } .sp-sidebar { width: 100% !important; } }

    .sp-sidebar { width: 240px; flex-shrink: 0; position: sticky; top: 32px; }
    .sp-filterHead { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid hsl(var(--border)); padding-bottom: 12px; margin-bottom: 24px; }
    .sp-filterCount { font-size: 14px; color: hsl(var(--muted-fg)); }
    .sp-filterClear { font-size: 12px; font-weight: 500; color: hsl(var(--primary)); text-decoration: none; }
    .sp-filterClear:hover { text-decoration: underline; }
    .sp-filterTitle { font-size: 14px; font-weight: 600; color: hsl(var(--fg)); margin: 0 0 16px; }
    .sp-filterList { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
    .sp-filterItem a {
        display: flex; align-items: center; gap: 8px; text-decoration: none; color: hsl(var(--fg));
        font-size: 14px; padding: 4px 0; transition: color 0.15s;
    }
    .sp-filterItem a:hover { color: hsl(var(--primary)); }
    .sp-filterItem .sp-count { font-size: 12px; color: hsl(var(--muted-fg)); margin-left: auto; }
    .sp-filterItem input[type="checkbox"] { accent-color: hsl(var(--primary)); cursor: pointer; width: 16px; height: 16px; }
    .sp-filterSection { margin-bottom: 24px; }
    .sp-filterDivider { border-top: 1px solid hsl(var(--border)); padding-top: 16px; }

    /* Martfury-style Category Filter */
    .sp-categoryList { list-style: none; padding: 0; margin: 0; border-top: 1px solid hsl(var(--border)); margin-top: 12px; }
    .sp-categoryItem { border-bottom: 1px solid hsl(var(--border)); position: relative; }
    .sp-categoryRow { display: flex; align-items: center; justify-content: space-between; position: relative; }
    .sp-categoryLink { 
        display: block; flex: 1; padding: 12px 0; font-size: 14px; font-weight: 500; 
        color: hsl(var(--fg)); text-decoration: none; transition: color 0.15s;
    }
    .sp-categoryLink:hover, .sp-categoryLink.active { color: hsl(var(--primary)); }
    .sp-categoryItem.active > .sp-categoryRow > .sp-categoryLink { font-weight: 700; color: hsl(var(--primary)); }
    
    .sp-catToggle { 
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; 
        cursor: pointer; position: absolute; right: 0; top: 8px; z-index: 2;
    }
    .sp-catToggle::before { content: '+'; font-size: 18px; color: hsl(var(--muted-fg)); font-weight: 300; }
    .sp-catToggle.active::before { content: '-'; }
    
    .sp-catChildren { 
        list-style: none; padding: 0 0 12px 16px; margin: 0; display: none; 
        border-top: 1px dashed hsl(var(--border));
    }
    .sp-catChildren.show { display: block; }
    .sp-subCatLink { 
        display: block; padding: 8px 0; font-size: 13px; color: hsl(var(--muted-fg)); 
        text-decoration: none; transition: color 0.15s;
    }
    .sp-subCatLink:hover, .sp-subCatLink.active { color: hsl(var(--primary)); }

    .sp-backLink { 
        display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; 
        color: hsl(var(--muted-fg)); text-decoration: none; padding-bottom: 12px; border-bottom: 1px solid hsl(var(--border)); 
        margin-bottom: 8px;
    }
    .sp-backLink:hover { color: hsl(var(--primary)); }

    .sp-main { flex: 1; min-width: 0; }
    .sp-sortRow { display: flex; justify-content: flex-end; align-items: center; gap: 8px; margin-bottom: 24px; }
    .sp-sortLabel { font-size: 13px; color: hsl(var(--muted-fg)); }
    .sp-sortSelect {
        border: 1px solid hsl(var(--border)); background: hsl(var(--bg)); font-size: 14px; font-weight: 500;
        color: hsl(var(--fg)); outline: none; cursor: pointer; padding: 8px 12px; border-radius: 6px;
    }

    /* Product Grid - MUST be row-based grid */
    .sp-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    @media (max-width: 768px) { .sp-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
    @media (max-width: 480px) { .sp-grid { grid-template-columns: 1fr; } }

    .sp-card {
        background: white; border-radius: 8px; border: 1px solid #f3f4f6;
        padding: 16px; transition: all 0.3s ease; cursor: pointer; display: flex; flex-direction: column;
    }
    .sp-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.08); border-color: transparent; }
    .sp-imgWrap { aspect-ratio: 1; margin-bottom: 12px; overflow: hidden; border-radius: 8px; display: flex; justify-content: center; align-items: center; position: relative; padding: 8px; }
    .sp-img { width: 100%; height: 100%; object-fit: contain; transition: transform 0.4s ease; }
    .sp-card:hover .sp-img { transform: scale(1.05); }
    .sp-badge {
        position: absolute; top: 12px; left: 12px; display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .sp-badge-sale { background: #E5222E; color: white; }
    .sp-badge-sold { background: #111827; color: white; }

    .sp-name { font-size: 14px; font-weight: 600; color: #1F2937; margin: 0 0 8px; text-decoration: none; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 40px; }
    .sp-name:hover { color: #1F8A43; }
    .sp-stock { font-size: 12px; color: #1F8A43; font-weight: 500; margin: 0 0 4px; }
    .sp-stock.out { color: #E5222E; }
    .sp-priceRow { display: flex; align-items: center; gap: 8px; margin-top: auto; padding-bottom: 16px; flex-wrap: wrap; }
    .sp-price { font-size: 20px; font-weight: 700; color: #111827; }
    .sp-priceSale { color: #E5222E; }
    .sp-priceOld { text-decoration: line-through; color: #6B7280; font-size: 13px; font-weight: normal; }
    .sp-addBtn {
        width: 100%; padding: 10px 0; font-size: 13px; font-weight: 700;
        border-radius: 4px; border: none; cursor: pointer; text-align: center;
        background: #1F8A43; color: white; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: auto;
    }
    .sp-addBtn:hover { background: #176d34; }
    .sp-addBtn:disabled { background: #E5E7EB; color: #6B7280; cursor: not-allowed; }

    .sp-pagination { margin-top: 40px; padding-top: 24px; border-top: 1px solid hsl(var(--border)); display: flex; justify-content: center; }
</style>

<!-- Page Header -->
<div class="sp-header">
    <h1 class="sp-headerTitle">Products</h1>
    <p class="sp-headerDesc">Explore our curated selection of quality essentials.</p>
</div>

<div class="sp-wrap">
    <!-- Sidebar Filters -->
    <aside class="sp-sidebar">
        <form action="{{ route('products.index') }}" method="GET" id="filterForm">
            <!-- Reuse existing params like sort -->
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="sp-filterHead">
                <span class="sp-filterCount">{{ $products->total() }} products</span>
                <a href="{{ route('products.index') }}" class="sp-filterClear">Clear all</a>
            </div>

            <!-- Categories -->
            <div class="sp-filterSection">
                <h3 class="sp-filterTitle">Category</h3>
                @php 
                    $activeCategoryId = request('category');
                    $activeCat = $activeCategoryId ? \App\Models\Category::with('parent')->find($activeCategoryId) : null;
                    
                    // If we have an active category, Martfury shows a "focused" view
                    if ($activeCat) {
                        $parent = $activeCat->parent_id ? $activeCat->parent : $activeCat;
                        $categories = \App\Models\Category::where('id', $parent->id)->with('children.children')->get();
                        $showBack = true;
                    } else {
                        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
                        $showBack = false;
                    }
                @endphp

                @if($showBack)
                    <a href="{{ route('products.index') }}" class="sp-backLink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        All Categories
                    </a>
                @endif

                <ul class="sp-categoryList">
                    @foreach ($categories as $cat)
                        @php 
                            $isParentActive = ($activeCategoryId == $cat->id || ($activeCat && $activeCat->parent_id == $cat->id));
                        @endphp
                        <li class="sp-categoryItem {{ $isParentActive ? 'active' : '' }}">
                            <div class="sp-categoryRow">
                                <a href="{{ route('products.index', ['category' => $cat->id] + request()->except('category', 'page')) }}" 
                                   class="sp-categoryLink {{ $activeCategoryId == $cat->id ? 'active' : '' }}">
                                   {{ $cat->name }}
                                </a>
                                @if($cat->children->count() > 0)
                                    <div class="sp-catToggle {{ $isParentActive ? 'active' : '' }}" onclick="toggleCat(event, {{ $cat->id }})"></div>
                                @endif
                            </div>
                            @if($cat->children->count() > 0)
                                <ul class="sp-catChildren {{ $isParentActive ? 'show' : '' }}" id="children-{{ $cat->id }}">
                                    @foreach($cat->children as $sub)
                                        <li class="sp-subCategoryItem">
                                            <a href="{{ route('products.index', ['category' => $sub->id] + request()->except('category', 'page')) }}" 
                                               class="sp-subCatLink {{ $activeCategoryId == $sub->id ? 'active' : '' }}">
                                               {{ $sub->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Price Range -->
            <div class="sp-filterSection sp-filterDivider">
                <h3 class="sp-filterTitle">Price Range</h3>
                <div class="flex items-center gap-2">
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full border border-gray-200 rounded-md p-2 text-sm outline-none focus:border-black">
                    <span class="text-gray-400">-</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full border border-gray-200 rounded-md p-2 text-sm outline-none focus:border-black">
                </div>
            </div>

            <!-- Availability -->
            <div class="sp-filterSection sp-filterDivider">
                <h3 class="sp-filterTitle">Availability</h3>
                <ul class="sp-filterList">
                    <li class="sp-filterItem">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="stock" value="in" {{ request('stock') === 'in' ? 'checked' : '' }} class="w-4 h-4 accent-black">
                            <span class="text-sm">In stock</span>
                        </label>
                    </li>
                    <li class="sp-filterItem">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="stock" value="out" {{ request('stock') === 'out' ? 'checked' : '' }} class="w-4 h-4 accent-black">
                            <span class="text-sm">Out of stock</span>
                        </label>
                    </li>
                </ul>
            </div>

            <button type="submit" class="w-full bg-black text-white py-3 rounded-lg text-sm font-bold mt-4 hover:bg-gray-800 transition-colors uppercase tracking-wider">
                Apply Filters
            </button>
        </form>
    </aside>

    <!-- Product Grid -->
    <div class="sp-main">
        <!-- Sort -->
        <div class="sp-sortRow">
            <span class="sp-sortLabel">Sort by</span>
            <select class="sp-sortSelect" onchange="const form = document.getElementById('filterForm'); const input = document.createElement('input'); input.type='hidden'; input.name='sort'; input.value=this.value; form.appendChild(input); form.submit();">
                <option value="featured" {{ request('sort', 'featured') === 'featured' ? 'selected' : '' }}>Featured</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Alphabetically, A-Z</option>
                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price, low to high</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price, high to low</option>
            </select>
        </div>

        <div class="sp-grid">
            @forelse ($products as $product)
                <div class="sp-card" onclick="window.location='{{ route('products.show', $product) }}'">
                    <div class="sp-imgWrap">
                        <img src="{{ $product->image ?: 'https://placehold.co/400x400/f7f5ed/1f332a?text='.urlencode($product->name) }}" class="sp-img" alt="{{ $product->name }}">
                        @if($product->sale_price)
                            <span class="sp-badge sp-badge-sale">Sale</span>
                        @endif
                        @if($product->manage_stock && $product->stock_quantity <= 0)
                            <span class="sp-badge sp-badge-sold">Sold Out</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('products.show', $product) }}" class="sp-name" onclick="event.stopPropagation()">{{ $product->name }}</a>
                    
                    @if($product->manage_stock && $product->stock_quantity <= 0)
                        <div class="sp-stock out">Out of stock</div>
                    @else
                        <div class="sp-stock">In stock</div>
                    @endif
                    
                    <div class="sp-priceRow">
                        @if($product->sale_price)
                            <span class="sp-price sp-priceSale">${{ number_format($product->sale_price, 2) }}</span>
                            <span class="sp-priceOld">${{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="sp-price">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    @if(!($product->manage_stock && $product->stock_quantity <= 0))
                        <form action="{{ route('cart.add', $product) }}" method="POST" style="margin: 0; margin-top: auto;" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="sp-addBtn">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <button disabled class="sp-addBtn">Sold Out</button>
                    @endif
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 64px 0; color: hsl(var(--muted-fg));">
                    <p style="font-size: 18px; margin: 0 0 8px;">No products found</p>
                    <a href="{{ route('products.index') }}" style="color: hsl(var(--primary)); text-decoration: none; font-size: 14px;">Clear filters</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="sp-pagination">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
function toggleCat(event, id) {
    event.stopPropagation();
    event.preventDefault();
    const toggle = event.currentTarget;
    const children = document.getElementById('children-' + id);
    
    toggle.classList.toggle('active');
    children.classList.toggle('show');
}
</script>
@endsection
