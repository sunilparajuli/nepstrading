@extends('layouts.app')

@section('title', 'Nepstrading - Authentic Nepali & Asian Groceries Australia')
@section('meta_description', 'Shop authentic Nepali, Indian and Asian groceries delivered across Australia. Fresh produce, spices, lentils, snacks and more.')

@section('content')
<style>
    /* === MAHALMART PREMIUM DESIGN === */
    :root {
        --mahal-green: #118443;
        --mahal-blue: #2563eb;
        --mahal-border: #e5e7eb;
        --mahal-bg: #ffffff;
        --mahal-img-bg: #f7f7f5;
        --mahal-text: #111827;
        --mahal-muted: #6b7280;
    }

    .hp-section { max-width: 1440px; margin: 0 auto; padding: 48px 20px; }

    /* Section Header */
    .hp-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid var(--mahal-border); }
    .hp-title { font-family: 'DM Serif Display', serif; font-size: 24px; font-weight: 700; color: #1a3c34; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; }
    .hp-view-all { font-size: 14px; font-weight: 600; color: var(--mahal-text); text-decoration: underline; text-underline-offset: 4px; }
    .hp-view-all:hover { color: var(--mahal-green); }

    /* Slider Shell */
    .hp-slider-container { position: relative; }
    .hp-slider-track { 
        display: flex; overflow-x: auto; scroll-behavior: smooth; 
        scrollbar-width: none; -ms-overflow-style: none;
        border-left: 1px solid var(--mahal-border);
    }
    .hp-slider-track::-webkit-scrollbar { display: none; }

    /* Nav Buttons */
    .hp-nav-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 40px; height: 40px; background: #fff; border: 1px solid var(--mahal-border);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 10; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        color: var(--mahal-text); font-size: 18px; transition: all 0.2s;
    }
    .hp-nav-btn:hover { background: var(--mahal-green); color: #fff; border-color: var(--mahal-green); }
    .hp-nav-btn.prev { left: -20px; }
    .hp-nav-btn.next { right: -20px; }
    @media (max-width: 768px) { .hp-nav-btn { display: none; } }

    /* Product Card */
    .hp-card {
        flex: 0 0 calc(100% / 7); min-width: 180px;
        display: flex; flex-direction: column;
        background: var(--mahal-bg);
        border: 1px solid var(--mahal-border);
        border-left: none;
        position: relative; transition: all 0.2s;
        cursor: pointer;
    }
    @media (max-width: 1400px) { .hp-card { flex: 0 0 calc(100% / 6); } }
    @media (max-width: 1100px) { .hp-card { flex: 0 0 calc(100% / 4); } }
    @media (max-width: 800px) { .hp-card { flex: 0 0 calc(100% / 3); } }
    @media (max-width: 500px) { .hp-card { flex: 0 0 calc(100% / 2); } }

    .hp-card:hover { z-index: 2; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-color: var(--mahal-green); }

    /* Badge */
    .hp-badge { 
        position: absolute; top: 12px; left: 12px; z-index: 5;
        background: var(--mahal-blue); color: #fff; font-size: 10px; font-weight: 700;
        padding: 4px 8px; border-radius: 2px; text-transform: uppercase;
        display: flex; align-items: center; gap: 4px;
    }
    .hp-badge svg { width: 10px; height: 10px; fill: #fff; }

    /* Image Wrapper */
    .hp-img-wrap { width: 100%; aspect-ratio: 1; background: var(--mahal-img-bg); display: flex; align-items: center; justify-content: center; padding: 16px; overflow: hidden; }
    .hp-img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.4s ease; }
    .hp-card:hover .hp-img { transform: scale(1.08); }

    /* Card Content */
    .hp-content { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .hp-name { 
        font-size: 14px; font-weight: 700; color: var(--mahal-text); line-height: 1.4; 
        margin-bottom: 8px; height: 40px; display: -webkit-box; -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; overflow: hidden; 
    }
    
    /* Stock Status */
    .hp-stock { font-size: 12px; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }
    .hp-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .hp-stock-in { color: #118443; } .hp-stock-in .hp-dot { background: #118443; }
    .hp-stock-low { color: #d97706; } .hp-stock-low .hp-dot { background: #d97706; }

    /* Price */
    .hp-price-row { margin-bottom: 16px; display: flex; align-items: flex-start; }
    .hp-currency { font-size: 14px; font-weight: 800; margin-top: 2px; }
    .hp-price-main { font-size: 22px; font-weight: 800; color: var(--mahal-text); }
    .hp-price-cents { font-size: 12px; font-weight: 800; margin-top: 2px; }
    .hp-price-old { font-size: 14px; color: var(--mahal-muted); text-decoration: line-through; margin-left: 8px; margin-top: 4px; }

    /* Add Button */
    .hp-add-btn {
        width: 100%; background: var(--mahal-green); color: #fff; border: none; 
        padding: 12px; font-size: 12px; font-weight: 800; text-transform: uppercase; 
        letter-spacing: 0.05em; border-radius: 4px; cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: auto;
    }
    .hp-add-btn:hover { background: #0d6b35; transform: translateY(-2px); }

    /* Category Circles */
    .hp-cat-row { display: flex; gap: 24px; overflow-x: auto; padding: 12px 0; scrollbar-width: none; }
    .hp-cat-row::-webkit-scrollbar { display: none; }
    .hp-cat-item { flex: 0 0 auto; display: flex; flex-direction: column; align-items: center; gap: 12px; text-decoration: none; width: 100px; }
    .hp-cat-circle { 
        width: 100px; height: 100px; border-radius: 50%; background: #fff; 
        border: 1.5px solid var(--mahal-border); display: flex; align-items: center; 
        justify-content: center; padding: 18px; transition: all 0.3s; 
    }
    .hp-cat-item:hover .hp-cat-circle { border-color: var(--mahal-green); transform: translateY(-5px); box-shadow: 0 10px 20px rgba(17,132,67,0.1); }
    .hp-cat-img { max-width: 100%; max-height: 100%; object-fit: contain; }
    .hp-cat-label { font-size: 13px; font-weight: 700; color: var(--mahal-text); text-align: center; }

    /* Trust Marquee */
    .hp-marquee { background: #1a3c34; padding: 16px 0; overflow: hidden; white-space: nowrap; }
    .hp-marquee-track { display: inline-flex; animation: marquee 40s linear infinite; }
    .hp-marquee-item { display: inline-flex; align-items: center; gap: 12px; padding: 0 40px; font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 0.1em; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    /* Features Section */
    .hp-features { background: #f9fafb; border-top: 1px solid var(--mahal-border); border-bottom: 1px solid var(--mahal-border); padding: 32px 0; }
    .hp-features-inner { max-width: 1440px; margin: 0 auto; padding: 0 20px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    @media (max-width: 768px) { .hp-features-inner { grid-template-columns: repeat(2, 1fr); } }
    .hp-feature-box { display: flex; align-items: center; gap: 16px; }
    .hp-feature-icon { width: 44px; height: 44px; background: var(--mahal-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hp-feature-icon svg { width: 22px; height: 22px; stroke: #fff; }
    .hp-feature-title { font-weight: 700; font-size: 15px; color: var(--mahal-text); margin: 0; }
    .hp-feature-desc { font-size: 12px; color: var(--mahal-muted); margin: 0; }
</style>

@php
    $newArrivals = \App\Models\Product::where('status', 'active')->latest()->take(14)->get();
    $featured = \App\Models\Product::where('status', 'active')->where('is_featured', true)->inRandomOrder()->take(14)->get();
    $categories = \App\Models\Category::whereNull('parent_id')->where('is_enabled', true)->take(14)->get();
@endphp

{{-- Hero Section --}}
@include('partials.home.hero')

{{-- Features Bar --}}
<div class="hp-features">
    <div class="hp-features-inner">
        <div class="hp-feature-box">
            <div class="hp-feature-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            <div><p class="hp-feature-title">Free Delivery</p><p class="hp-feature-desc">On orders over $99</p></div>
        </div>
        <div class="hp-feature-box">
            <div class="hp-feature-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <div><p class="hp-feature-title">Secure Payment</p><p class="hp-feature-desc">100% protected</p></div>
        </div>
        <div class="hp-feature-box">
            <div class="hp-feature-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div><p class="hp-feature-title">Fast Dispatch</p><p class="hp-feature-desc">Ordered before 1pm</p></div>
        </div>
        <div class="hp-feature-box">
            <div class="hp-feature-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><p class="hp-feature-title">Australia Wide</p><p class="hp-feature-desc">Nationwide shipping</p></div>
        </div>
    </div>
</div>

{{-- Shop By Category --}}
<section class="hp-section">
    <div class="hp-header">
        <h2 class="hp-title">Shop By Category</h2>
        <a href="{{ route('products.index') }}" class="hp-view-all">View all &rarr;</a>
    </div>
    <div class="hp-cat-row">
        @foreach($categories as $cat)
            @php
                $lowerName = strtolower($cat->name);
                $defaultImg = 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=200&auto=format&fit=crop'; // Default grocery
                
                if (str_contains($lowerName, 'rice')) $defaultImg = 'https://images.unsplash.com/photo-1586201375761-83865001e31c?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'lentil')) $defaultImg = 'https://images.unsplash.com/photo-1515942400420-2b98fed1f515?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'spice')) $defaultImg = 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'momo') || str_contains($lowerName, 'dumpling')) $defaultImg = 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'noodle')) $defaultImg = 'https://images.unsplash.com/photo-1552611052-d59a0d9741bc?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'meat')) $defaultImg = 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'oil') || str_contains($lowerName, 'ghee')) $defaultImg = 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'sweet') || str_contains($lowerName, 'biscuit')) $defaultImg = 'https://images.unsplash.com/photo-1532347921448-338425dfb88d?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'snack')) $defaultImg = 'https://images.unsplash.com/photo-1599490659213-e2b9527bb087?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'drink') || str_contains($lowerName, 'juice')) $defaultImg = 'https://images.unsplash.com/photo-1600271886399-d449075a7304?q=80&w=200&auto=format&fit=crop';
                elseif (str_contains($lowerName, 'puja') || str_contains($lowerName, 'incense')) $defaultImg = 'https://images.unsplash.com/photo-1609139006981-d072433065c8?q=80&w=200&auto=format&fit=crop';
            @endphp
            <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="hp-cat-item">
                <div class="hp-cat-circle">
                    @php
                        $imgSrc = $cat->image ? asset('storage/' . $cat->image) : $defaultImg;
                    @endphp
                    <img src="{{ $imgSrc }}" alt="{{ $cat->name }}" class="hp-cat-img" loading="lazy" />
                </div>
                <span class="hp-cat-label">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- New Arrivals --}}
<section class="hp-section" style="padding-top: 0;">
    <div class="hp-header">
        <h2 class="hp-title">New Arrivals</h2>
        <a href="{{ route('products.index') }}" class="hp-view-all">View all &rarr;</a>
    </div>
    <div class="hp-slider-container">
        <button class="hp-nav-btn prev" onclick="hpScroll('arrivals', -1)">&#8592;</button>
        <button class="hp-nav-btn next" onclick="hpScroll('arrivals', 1)">&#8594;</button>
        <div class="hp-slider-track" id="arrivals">
            @foreach($newArrivals as $p)
                @php
                    $price = $p->sale_price ?: $p->price;
                    $parts = explode('.', number_format($price, 2));
                @endphp
                <div class="hp-card" onclick="window.location='{{ route('products.show', $p) }}'">
                    @if($p->is_featured)
                        <div class="hp-badge">
                            <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            New arrival
                        </div>
                    @endif
                    <div class="hp-img-wrap">
                        <img src="{{ $p->image ?: 'https://placehold.co/400x400/f7f7f5/1a3c34?text='.urlencode($p->name) }}" alt="{{ $p->name }}" class="hp-img" loading="lazy" />
                    </div>
                    <div class="hp-content">
                        <h3 class="hp-name">{{ $p->name }}</h3>
                        <div class="hp-stock {{ $p->manage_stock && $p->stock_quantity <= 5 ? 'hp-stock-low' : 'hp-stock-in' }}">
                            <span class="hp-dot"></span> {{ $p->manage_stock && $p->stock_quantity <= 5 ? 'Low stock' : 'In stock' }}
                        </div>
                        <div class="hp-price-row">
                            <span class="hp-currency">$</span>
                            <span class="hp-price-main">{{ $parts[0] }}</span>
                            <sup class="hp-price-cents">{{ $parts[1] }}</sup>
                            @if($p->sale_price)
                                <span class="hp-price-old">${{ number_format($p->price, 2) }}</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="hp-add-btn">+ Add to Cart</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Products --}}
@if($featured->count())
<section class="hp-section" style="padding-top: 0; background-color: #f9fafb;">
    <div class="hp-header">
        <h2 class="hp-title">Featured Products</h2>
        <a href="{{ route('products.index') }}" class="hp-view-all">View all &rarr;</a>
    </div>
    <div class="hp-slider-container">
        <button class="hp-nav-btn prev" onclick="hpScroll('featured', -1)">&#8592;</button>
        <button class="hp-nav-btn next" onclick="hpScroll('featured', 1)">&#8594;</button>
        <div class="hp-slider-track" id="featured">
            @foreach($featured as $p)
                @php
                    $price = $p->sale_price ?: $p->price;
                    $parts = explode('.', number_format($price, 2));
                @endphp
                <div class="hp-card" onclick="window.location='{{ route('products.show', $p) }}'">
                    <div class="hp-img-wrap">
                        <img src="{{ $p->image ?: 'https://placehold.co/400x400/f7f7f5/1a3c34?text='.urlencode($p->name) }}" alt="{{ $p->name }}" class="hp-img" loading="lazy" />
                    </div>
                    <div class="hp-content">
                        <h3 class="hp-name">{{ $p->name }}</h3>
                        <div class="hp-stock {{ $p->manage_stock && $p->stock_quantity <= 5 ? 'hp-stock-low' : 'hp-stock-in' }}">
                            <span class="hp-dot"></span> {{ $p->manage_stock && $p->stock_quantity <= 5 ? 'Low stock' : 'In stock' }}
                        </div>
                        <div class="hp-price-row">
                            <span class="hp-currency">$</span>
                            <span class="hp-price-main">{{ $parts[0] }}</span>
                            <sup class="hp-price-cents">{{ $parts[1] }}</sup>
                            @if($p->sale_price)
                                <span class="hp-price-old">${{ number_format($p->price, 2) }}</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="hp-add-btn">+ Add to Cart</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Trust Marquee --}}
<div class="hp-marquee">
    <div class="hp-marquee-track">
        @foreach(range(1, 4) as $i)
            <span class="hp-marquee-item">● Authentic Products</span>
            <span class="hp-marquee-item">● Fresh Groceries</span>
            <span class="hp-marquee-item">● Free Delivery Over $99</span>
            <span class="hp-marquee-item">● Secure Payment</span>
            <span class="hp-marquee-item">● Australia Wide Shipping</span>
            <span class="hp-marquee-item">● 24/7 Support</span>
        @endforeach
    </div>
</div>

<script>
    function hpScroll(id, dir) {
        const track = document.getElementById(id);
        const scrollAmount = track.clientWidth * 0.8;
        track.scrollBy({ left: dir * scrollAmount, behavior: 'smooth' });
    }
</script>
@endsection
