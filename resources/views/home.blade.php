@extends('layouts.app')

@section('title', 'Nepstrading - Authentic Nepali & Asian Groceries Australia')
@section('meta_description', 'Shop authentic Nepali, Indian and Asian groceries delivered across Australia. Fresh produce, spices, lentils, snacks and more.')

@section('content')
<style>
    .hp-section { max-width: 1400px; margin: 0 auto; padding: 48px 24px; }
    .hp-sectionHeader { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; }
    .hp-sectionTitle { font-family: 'DM Serif Display', serif; font-size: 26px; font-weight: 700; color: hsl(var(--fg)); margin: 0; text-transform: uppercase; letter-spacing: 0.03em; }
    .hp-viewAll { font-size: 14px; font-weight: 600; color: hsl(var(--fg)); text-decoration: underline; text-underline-offset: 3px; white-space: nowrap; }
    .hp-viewAll:hover { color: hsl(var(--primary)); }

    /* Slider wrapper */
    .hp-sliderWrapper { position: relative; background: hsl(var(--bg)); border: 1px solid hsl(var(--border)); border-radius: 4px; overflow: visible; }
    .hp-slider { display: flex; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; }
    .hp-slider::-webkit-scrollbar { display: none; }

    /* Nav arrows */
    .hp-navBtn {
        position: absolute; top: 40%; transform: translateY(-50%);
        width: 40px; height: 40px; background: hsl(var(--bg)); border: 1px solid hsl(var(--border));
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 20; box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        color: hsl(var(--fg)); font-size: 16px; transition: all 0.2s;
    }
    .hp-navBtn:hover { background: hsl(var(--primary)); color: hsl(var(--primary-fg)); border-color: hsl(var(--primary)); }
    .hp-navBtn.prev { left: -20px; }
    .hp-navBtn.next { right: -20px; }
    @media(max-width:768px) { .hp-navBtn { display: none; } }

    /* Card — exact Mahalmart proportions */
    .hp-prodCard {
        flex: 0 0 calc(100% / 7); min-width: 160px; max-width: 200px;
        display: flex; flex-direction: column;
        border-right: 1px solid hsl(var(--border));
        background: hsl(var(--bg));
        cursor: pointer; position: relative;
    }
    .hp-prodCard:last-child { border-right: none; }
    @media(max-width:1200px){ .hp-prodCard { flex: 0 0 20%; } }
    @media(max-width:900px) { .hp-prodCard { flex: 0 0 33.33%; } }
    @media(max-width:600px) { .hp-prodCard { flex: 0 0 50%; } }

    /* Badge */
    .hp-badge {
        position: absolute; top: 10px; left: 10px; z-index: 5;
        background: hsl(var(--primary)); color: hsl(var(--primary-fg));
        font-size: 9px; font-weight: 700; padding: 3px 7px;
        border-radius: 2px; text-transform: uppercase; letter-spacing: 0.05em;
        display: flex; align-items: center; gap: 4px;
    }
    .hp-badge.sale { background: hsl(0 84% 50%); color: #fff; left: auto; right: 10px; }

    /* Image area — light grey like Mahalmart */
    .hp-imgWrap {
        width: 100%; aspect-ratio: 1;
        background: #f7f7f5;
        display: flex; align-items: center; justify-content: center;
        padding: 16px; overflow: hidden;
    }
    .hp-img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.35s ease; }
    .hp-prodCard:hover .hp-img { transform: scale(1.06); }

    /* Info below image */
    .hp-prodInner { padding: 12px 14px 14px; display: flex; flex-direction: column; flex: 1; }
    .hp-prodName {
        font-size: 13px; font-weight: 700; color: hsl(var(--fg));
        line-height: 1.45; margin-bottom: 6px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 38px;
    }

    /* Stock */
    .hp-stock { font-size: 11px; font-weight: 600; margin-bottom: 10px; display: flex; align-items: center; gap: 5px; }
    .hp-stockDot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .hp-instock { color: hsl(var(--primary)); } .hp-instock .hp-stockDot { background: hsl(var(--primary)); }
    .hp-lowstock { color: #d97706; } .hp-lowstock .hp-stockDot { background: #d97706; }

    /* Price — dollar sign small, number big, cents superscript */
    .hp-priceRow { margin-bottom: 12px; display: flex; align-items: baseline; gap: 8px; }
    .hp-price { font-size: 20px; font-weight: 800; color: hsl(var(--fg)); }
    .hp-priceOld { font-size: 12px; color: hsl(var(--muted-fg)); text-decoration: line-through; }

    /* Add to Cart button — full width dark green */
    .hp-addBtn {
        width: 100%; background: hsl(var(--primary)); color: hsl(var(--primary-fg));
        border: none; padding: 10px 0; font-size: 11px; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.07em; border-radius: 3px;
        cursor: pointer; transition: background 0.2s, transform 0.15s;
        margin-top: auto;
    }
    .hp-addBtn:hover { background: hsl(145 63% 28%); transform: translateY(-1px); }

    /* Category Row */
    .hp-catRow { display: flex; gap: 12px; overflow-x: auto; padding: 8px 2px 16px; scrollbar-width: none; }
    .hp-catRow::-webkit-scrollbar { display: none; }
    .hp-catCard { flex: 0 0 auto; display: flex; flex-direction: column; align-items: center; gap: 10px; text-decoration: none; }
    .hp-catCircle {
        width: 100px; height: 100px; border-radius: 50%;
        background: hsl(var(--muted)); border: 1.5px solid hsl(var(--border));
        display: flex; align-items: center; justify-content: center; padding: 16px;
        transition: all 0.25s;
    }
    .hp-catCard:hover .hp-catCircle { border-color: hsl(var(--primary)); transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .hp-catImg { max-width: 100%; max-height: 100%; object-fit: contain; }
    .hp-catImgFallback { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
    .hp-catLabel { font-size: 12px; font-weight: 700; color: hsl(var(--fg)); text-align: center; max-width: 100px; line-height: 1.3; }

    /* Features bar */
    .hp-features { background: hsl(var(--muted)); border-top: 1px solid hsl(var(--border)); border-bottom: 1px solid hsl(var(--border)); padding: 28px 0; }
    .hp-featuresInner { max-width: 1400px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; }
    @media(max-width:768px) { .hp-featuresInner { grid-template-columns: repeat(2,1fr); } }
    .hp-featureItem { display: flex; align-items: center; gap: 14px; }
    .hp-featureIcon { width: 44px; height: 44px; background: hsl(var(--primary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .hp-featureTitle { font-weight: 700; font-size: 14px; color: hsl(var(--fg)); margin: 0 0 2px; }
    .hp-featureDesc { font-size: 12px; color: hsl(var(--muted-fg)); margin: 0; }

    /* Marquee */
    .hp-marquee { overflow: hidden; white-space: nowrap; background: hsl(var(--fg)); padding: 16px 0; margin-top: 56px; }
    .hp-marqueeTrack { display: inline-flex; animation: hp-marquee 35s linear infinite; }
    .hp-marqueeItem { display: inline-flex; align-items: center; gap: 10px; padding: 0 40px; font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.12em; }
    @keyframes hp-marquee { 0%{ transform: translateX(0); } 100%{ transform: translateX(-50%); } }
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
