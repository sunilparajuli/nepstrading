@extends('layouts.app')

@section('title', 'Nepstrading - Authentic Nepali & Asian Groceries Australia')
@section('meta_description', 'Shop authentic Nepali, Indian and Asian groceries delivered across Australia. Fresh produce, spices, lentils, snacks and more.')

@section('content')
<style>
    /* === MAHALMART-STYLE PREMIUM DESIGN === */
    .hp-section { max-width: 1400px; margin: 0 auto; padding: 48px 24px; }

    /* Section Header */
    .hp-sectionHeader { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; padding-bottom: 14px; border-bottom: 2px solid #f3f4f6; }
    .hp-sectionTitle { font-family: 'DM Serif Display', serif; font-size: 28px; font-weight: 700; color: #1a3c34; margin: 0; text-transform: uppercase; letter-spacing: 0.03em; }
    .hp-viewAll { font-size: 14px; font-weight: 600; color: #111; text-decoration: underline; text-underline-offset: 4px; white-space: nowrap; }
    .hp-viewAll:hover { color: #118443; }

    /* Slider */
    .hp-sliderWrapper { position: relative; }
    .hp-slider { display: flex; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; gap: 0; }
    .hp-slider::-webkit-scrollbar { display: none; }
    .hp-navBtn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 44px; height: 44px; background: #fff; border: 1px solid #e5e7eb;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 10; box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        color: #111; font-size: 18px; transition: all 0.2s;
    }
    .hp-navBtn:hover { background: #118443; color: #fff; border-color: #118443; }
    .hp-navBtn.prev { left: -22px; }
    .hp-navBtn.next { right: -22px; }
    @media(max-width:768px) { .hp-navBtn { display: none; } }

    /* Product Card */
    .hp-prodCard {
        flex: 0 0 12.5%; min-width: 180px;
        display: flex; flex-direction: column;
        border-right: 1px solid #f3f4f6;
        position: relative; transition: all 0.2s;
        cursor: pointer;
    }
    @media(max-width:1200px){ .hp-prodCard { flex: 0 0 20%; } }
    @media(max-width:900px){ .hp-prodCard { flex: 0 0 33.33%; } }
    @media(max-width:640px){ .hp-prodCard { flex: 0 0 50%; } }

    .hp-prodInner { padding: 16px; display: flex; flex-direction: column; height: 100%; }
    .hp-prodCard:hover .hp-prodInner { box-shadow: 0 8px 32px rgba(0,0,0,0.08); background: #fff; }

    .hp-badge { position: absolute; top: 16px; left: 16px; z-index: 5;
        background: #2563eb; color: #fff; font-size: 10px; font-weight: 700;
        padding: 4px 8px; border-radius: 2px; text-transform: uppercase; letter-spacing: 0.05em; }
    .hp-badge.sale { background: #dc2626; }

    .hp-imgWrap { width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; overflow: hidden; }
    .hp-img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.4s ease; }
    .hp-prodCard:hover .hp-img { transform: scale(1.07); }

    .hp-prodName { font-size: 14px; font-weight: 700; color: #111827; line-height: 1.4; margin-bottom: 8px; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    .hp-stock { font-size: 12px; font-weight: 600; margin-bottom: 10px; display: flex; align-items: center; gap: 5px; }
    .hp-stockDot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .hp-instock { color: #118443; } .hp-instock .hp-stockDot { background: #118443; }
    .hp-lowstock { color: #d97706; } .hp-lowstock .hp-stockDot { background: #d97706; }

    .hp-priceRow { margin-bottom: 14px; }
    .hp-price { font-size: 22px; font-weight: 800; color: #111827; }
    .hp-priceOld { font-size: 13px; color: #9ca3af; text-decoration: line-through; margin-left: 6px; }

    .hp-addBtn {
        margin-top: auto; width: 100%; background: #118443; color: #fff;
        border: none; padding: 11px 0; font-size: 12px; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.06em; border-radius: 4px;
        cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .hp-addBtn:hover { background: #0d6b35; transform: translateY(-2px); }

    /* Category Row */
    .hp-catRow { display: flex; gap: 16px; overflow-x: auto; padding: 8px 0 16px; scrollbar-width: none; }
    .hp-catRow::-webkit-scrollbar { display: none; }
    .hp-catCard { flex: 0 0 auto; display: flex; flex-direction: column; align-items: center; gap: 12px; text-decoration: none; }
    .hp-catCircle {
        width: 110px; height: 110px; border-radius: 50%; background: #fff;
        border: 1.5px solid #e5e7eb; display: flex; align-items: center; justify-content: center;
        padding: 18px; transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .hp-catCard:hover .hp-catCircle { border-color: #118443; transform: translateY(-5px); box-shadow: 0 12px 24px rgba(17,132,67,0.12); }
    .hp-catImg { max-width: 100%; max-height: 100%; object-fit: contain; }
    .hp-catImgFallback { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
    .hp-catLabel { font-size: 13px; font-weight: 700; color: #111827; text-align: center; max-width: 110px; line-height: 1.3; }

    /* Features bar */
    .hp-features { background: #f9fafb; border-top: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; padding: 32px 0; }
    .hp-featuresInner { max-width: 1400px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; }
    @media(max-width:768px) { .hp-featuresInner { grid-template-columns: repeat(2,1fr); } }
    .hp-featureItem { display: flex; align-items: center; gap: 16px; }
    .hp-featureIcon { width: 48px; height: 48px; background: #118443; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hp-featureTitle { font-weight: 700; font-size: 15px; color: #111827; margin: 0 0 2px; }
    .hp-featureDesc { font-size: 13px; color: #6b7280; margin: 0; }

    /* Marquee */
    .hp-marquee { overflow: hidden; white-space: nowrap; background: #1a3c34; padding: 18px 0; margin-top: 64px; }
    .hp-marqueeTrack { display: inline-flex; animation: hp-marquee 35s linear infinite; }
    .hp-marqueeItem { display: inline-flex; align-items: center; gap: 12px; padding: 0 48px; font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.75); text-transform: uppercase; letter-spacing: 0.1em; }
    .hp-marqueeItem svg { color: rgba(255,255,255,0.5); }
    @keyframes hp-marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
</style>

@php
    $featuredProducts = \App\Models\Product::where('status', 'active')->where('is_featured', true)->inRandomOrder()->take(16)->get();
    $newArrivals = \App\Models\Product::where('status', 'active')->latest()->take(16)->get();
    $categories = \App\Models\Category::whereNull('parent_id')->where('is_enabled', true)->take(16)->get();
@endphp

{{-- Hero --}}
@include('partials.home.hero')

{{-- Features Bar --}}
<div class="hp-features">
    <div class="hp-featuresInner">
        <div class="hp-featureItem">
            <div class="hp-featureIcon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
            <div><p class="hp-featureTitle">Free Delivery</p><p class="hp-featureDesc">On orders over $99</p></div>
        </div>
        <div class="hp-featureItem">
            <div class="hp-featureIcon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
            <div><p class="hp-featureTitle">Secure Payment</p><p class="hp-featureDesc">100% protected</p></div>
        </div>
        <div class="hp-featureItem">
            <div class="hp-featureIcon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div><p class="hp-featureTitle">Fast Dispatch</p><p class="hp-featureDesc">Same day on orders before 1pm</p></div>
        </div>
        <div class="hp-featureItem">
            <div class="hp-featureIcon"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><p class="hp-featureTitle">Australia Wide</p><p class="hp-featureDesc">We deliver nationwide</p></div>
        </div>
    </div>
</div>

{{-- Shop By Category --}}
<section class="hp-section">
    <div class="hp-sectionHeader">
        <h2 class="hp-sectionTitle">Shop By Category</h2>
        <a href="{{ route('products.index') }}" class="hp-viewAll">View all &rarr;</a>
    </div>
    <div class="hp-catRow">
        @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="hp-catCard">
                <div class="hp-catCircle">
                    @if($cat->image)
                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="hp-catImg" />
                    @else
                        <div class="hp-catImgFallback" style="color: hsl({{ ($cat->id * 37) % 360 }}, 60%, 45%);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <span class="hp-catLabel">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- New Arrivals Slider --}}
<section class="hp-section" style="padding-top: 0;">
    <div class="hp-sectionHeader">
        <h2 class="hp-sectionTitle">New Arrivals</h2>
        <a href="{{ route('products.index') }}" class="hp-viewAll">View all &rarr;</a>
    </div>
    <div class="hp-sliderWrapper">
        <button class="hp-navBtn prev" onclick="scrollSlider('new-arrivals', -1)">&#8592;</button>
        <button class="hp-navBtn next" onclick="scrollSlider('new-arrivals', 1)">&#8594;</button>
        <div class="hp-slider" id="new-arrivals">
            @foreach($newArrivals as $p)
            <div class="hp-prodCard" onclick="window.location='{{ route('products.show', $p) }}'">
                @if($p->is_featured)<div class="hp-badge">New arrival</div>@endif
                @if($p->sale_price)<div class="hp-badge sale" style="top:16px; left:auto; right:16px;">Sale</div>@endif
                <div class="hp-prodInner">
                    <div class="hp-imgWrap">
                        <img src="{{ $p->image ?: 'https://placehold.co/300x300/f7f5ed/1f332a?text='.urlencode($p->name) }}" alt="{{ $p->name }}" class="hp-img" loading="lazy" />
                    </div>
                    <div class="hp-prodName">{{ $p->name }}</div>
                    @if($p->manage_stock && $p->stock_quantity <= 5)
                        <div class="hp-stock hp-lowstock"><span class="hp-stockDot"></span> Low stock</div>
                    @else
                        <div class="hp-stock hp-instock"><span class="hp-stockDot"></span> In stock</div>
                    @endif
                    <div class="hp-priceRow">
                        <span class="hp-price">${{ number_format($p->sale_price ?: $p->price, 2) }}</span>
                        @if($p->sale_price)<span class="hp-priceOld">${{ number_format($p->price, 2) }}</span>@endif
                    </div>
                    <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation();">
                        @csrf <input type="hidden" name="qty" value="1">
                        <button type="submit" class="hp-addBtn">+ Add to Cart</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Products Slider --}}
@if($featuredProducts->count())
<section class="hp-section" style="padding-top: 0; background: #f9fafb;">
    <div class="hp-sectionHeader">
        <h2 class="hp-sectionTitle">Featured Products</h2>
        <a href="{{ route('products.index') }}" class="hp-viewAll">View all &rarr;</a>
    </div>
    <div class="hp-sliderWrapper">
        <button class="hp-navBtn prev" onclick="scrollSlider('featured', -1)">&#8592;</button>
        <button class="hp-navBtn next" onclick="scrollSlider('featured', 1)">&#8594;</button>
        <div class="hp-slider" id="featured">
            @foreach($featuredProducts as $p)
            <div class="hp-prodCard" onclick="window.location='{{ route('products.show', $p) }}'">
                @if($p->sale_price)<div class="hp-badge sale">Sale</div>@endif
                <div class="hp-prodInner">
                    <div class="hp-imgWrap">
                        <img src="{{ $p->image ?: 'https://placehold.co/300x300/f7f5ed/1f332a?text='.urlencode($p->name) }}" alt="{{ $p->name }}" class="hp-img" loading="lazy" />
                    </div>
                    <div class="hp-prodName">{{ $p->name }}</div>
                    @if($p->manage_stock && $p->stock_quantity <= 5)
                        <div class="hp-stock hp-lowstock"><span class="hp-stockDot"></span> Low stock</div>
                    @else
                        <div class="hp-stock hp-instock"><span class="hp-stockDot"></span> In stock</div>
                    @endif
                    <div class="hp-priceRow">
                        <span class="hp-price">${{ number_format($p->sale_price ?: $p->price, 2) }}</span>
                        @if($p->sale_price)<span class="hp-priceOld">${{ number_format($p->price, 2) }}</span>@endif
                    </div>
                    <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation();">
                        @csrf <input type="hidden" name="qty" value="1">
                        <button type="submit" class="hp-addBtn">+ Add to Cart</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Scrolling Trust Bar --}}
<div class="hp-marquee">
    <div class="hp-marqueeTrack">
        @foreach(range(1,4) as $i)
            <span class="hp-marqueeItem"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg> Authentic Products</span>
            <span class="hp-marqueeItem"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg> Fresh Groceries</span>
            <span class="hp-marqueeItem"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg> Free Delivery Over $99</span>
            <span class="hp-marqueeItem"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg> Secure Payment</span>
            <span class="hp-marqueeItem"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg> Australia Wide Shipping</span>
            <span class="hp-marqueeItem"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8"/></svg> 24/7 Support</span>
        @endforeach
    </div>
</div>

<script>
function scrollSlider(id, dir) {
    const el = document.getElementById(id);
    el.scrollBy({ left: dir * 600, behavior: 'smooth' });
}
</script>
@endsection
