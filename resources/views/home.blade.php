@extends('layouts.app')

@section('title', 'Home')

@section('content')
<style>
    .s-heroSlide { position: relative; width: 100%; height: 460px; overflow: hidden; }
    .s-heroImgWrap { position: absolute; inset: 0; }
    .s-heroImg { width: 100%; height: 100%; object-fit: cover; }
    .s-heroOverlay {
        position: absolute; inset: 0;
        background: linear-gradient(90deg, hsla(var(--fg), 0.65) 0%, hsla(var(--fg), 0.2) 60%, transparent 100%);
    }
    .s-heroContent { position: absolute; inset: 0; display: flex; align-items: center; z-index: 10; }
    .s-heroInner { max-width: 1200px; margin: 0 auto; padding: 0 32px; width: 100%; }
    .s-heroSub { color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 8px; margin-top: 0; }
    .s-heroTitle { font-family: 'DM Serif Display', serif; font-size: clamp(28px, 5vw, 48px); color: #fff; margin-bottom: 12px; margin-top: 0;}
    .s-heroDesc { color: rgba(255,255,255,0.8); font-size: 18px; margin-bottom: 24px; margin-top: 0;}
    
    .s-heroBtnFilled {
        display: inline-block; padding: 10px 20px; font-size: 14px; font-weight: 600;
        border-radius: 6px; border: none; cursor: pointer; text-decoration: none;
        background: hsl(var(--primary)); color: hsl(var(--primary-fg)); transition: background 0.2s;
    }
    .s-heroBtnOutline {
        display: inline-block; padding: 10px 20px; font-size: 14px; font-weight: 600; text-decoration: none;
        border-radius: 6px; cursor: pointer; background: transparent; transition: background 0.2s;
        border: 1px solid #fff; color: #fff;
    }
    
    .s-featureStrip { padding: 16px 0; background: hsl(var(--muted)); }
    .s-featureInner {
        max-width: 1200px; margin: 0 auto; padding: 0 16px;
        display: flex; flex-wrap: wrap; justify-content: space-between; gap: 16px;
    }
    .s-featureItem { display: flex; align-items: center; gap: 12px; }
    .s-featureTitle { font-size: 14px; font-weight: 600; color: hsl(var(--fg)); }
    .s-featureDesc { font-size: 12px; color: hsl(var(--muted-fg)); }

    .s-section { max-width: 1200px; margin: 0 auto; padding: 40px 16px; }
    .s-sectionHeader { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
    .s-sectionTitle { font-family: 'DM Serif Display', serif; font-size: 24px; font-weight: 400; color: hsl(var(--fg)); margin: 0; }
    .s-viewAll { font-size: 14px; color: hsl(var(--primary)); font-weight: 500; text-decoration: none; }
    
    .s-catRow { display: flex; gap: 24px; overflow-x: auto; padding-bottom: 16px; }
    .s-catRow::-webkit-scrollbar { height: 4px; }
    .s-catRow::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    .s-catCard { display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer; flex-shrink: 0; text-decoration: none; }
    .s-catImg { width: 112px; height: 112px; border-radius: 50%; object-fit: cover; border: 2px solid hsl(var(--border)); transition: transform 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .s-catImgPlaceholder { 
        width: 112px; height: 112px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center; 
        font-size: 36px; font-family: 'DM Serif Display', serif; 
        border: 2px solid hsl(var(--border)); transition: transform 0.2s;
    }
    .s-catCard:hover .s-catImg, .s-catCard:hover .s-catImgPlaceholder { transform: scale(1.05); border-color: hsl(var(--primary)); }

    /* Bottom Marquee */
    .s-marquee {
        width: 100%; overflow: hidden; white-space: nowrap; 
        background: hsl(var(--muted)); padding: 40px 0;
        border-top: 1px solid hsl(var(--border)); border-bottom: 1px solid hsl(var(--border));
        margin-top: 64px;
    }
    .s-marqueeContent {
        display: inline-flex; align-items: center; gap: 64px;
        animation: marquee 40s linear infinite;
    }
    .s-marqueeItem {
        display: flex; align-items: center; gap: 16px;
        color: hsl(var(--muted-fg)); font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;
    }
    .s-marqueeIcon { width: 40px; height: 40px; opacity: 0.5; filter: grayscale(1); }
    
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .s-catLabel { font-size: 14px; font-weight: 500; color: hsl(var(--fg)); text-align: center; }

    .s-prodGrid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    @media (max-width: 1024px) { .s-prodGrid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .s-prodGrid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
    @media (max-width: 480px) { .s-prodGrid { grid-template-columns: 1fr; } }
    .s-prodCard {
        background: hsl(var(--bg)); border-radius: 12px; border: 1px solid hsl(var(--border));
        padding: 16px; transition: all 0.3s ease; cursor: pointer; display: flex; flex-direction: column;
    }
    .s-prodCard:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .s-prodImgWrap { aspect-ratio: 1; margin-bottom: 16px; overflow: hidden; border-radius: 8px; background: hsl(var(--muted)); }
    .s-prodImg { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .s-prodCard:hover .s-prodImg { transform: scale(1.05); }
    .s-prodName { font-size: 15px; font-weight: 600; color: hsl(var(--fg)); margin: 0; text-decoration: none; line-height: 1.4; }
    .s-prodWeight { font-size: 12px; color: hsl(var(--muted-fg)); margin: 4px 0 0 0; min-height: 18px; }
    .s-prodPriceRow { display: flex; align-items: center; gap: 8px; margin-top: 12px; }
    .s-prodPrice { font-size: 16px; font-weight: 700; color: hsl(var(--fg)); }
    .s-priceOriginal { text-decoration: line-through; color: hsl(var(--muted-fg)); font-size: 13px; }
    .s-addBtn {
        width: 100%; margin-top: 16px; padding: 10px 0; font-size: 13px; font-weight: 600;
        border-radius: 8px; border: none; cursor: pointer; text-align: center;
        background: hsl(var(--primary)); color: hsl(var(--primary-fg)); transition: all 0.2s;
    }
    .s-addBtn:hover { background: hsl(145 63% 28%); transform: translateY(-1px); box-shadow: 0 2px 8px hsla(145, 63%, 32%, 0.3); }
</style>

<!-- Hero -->
<div class="s-heroSlide">
    <div class="s-heroImgWrap">
        <!-- Using a placeholder hero image as React assets aren't local -->
        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=1600&h=600&fit=crop" alt="Hero" class="s-heroImg" />
        <div class="s-heroOverlay"></div>
    </div>
    <div class="s-heroContent">
        <div class="s-heroInner">
            <p class="s-heroSub">BBQ's made easy</p>
            <h2 class="s-heroTitle">A licence to grill!</h2>
            <p class="s-heroDesc">Welcome to flavor town</p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('products.index') }}" class="s-heroBtnFilled">Shop Meat & Fish</a>
                <a href="{{ route('products.index') }}" class="s-heroBtnOutline">Fresh Vegetables</a>
            </div>
        </div>
    </div>
</div>

<!-- Features -->
<div class="s-featureStrip">
    <div class="s-featureInner">
        <div class="s-featureItem">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="13" x="4" y="8" rx="2"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/><path d="M4 14h16"/></svg>
            <div>
                <div class="s-featureTitle">Free Local Delivery</div>
                <div class="s-featureDesc">On all orders over $50</div>
            </div>
        </div>
        <div class="s-featureItem">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
            <div>
                <div class="s-featureTitle">Freshness Guarantee</div>
                <div class="s-featureDesc">From farm to fork</div>
            </div>
        </div>
        <div class="s-featureItem">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            <div>
                <div class="s-featureTitle">Quick Checkout</div>
                <div class="s-featureDesc">Fill your basket faster</div>
            </div>
        </div>
        <div class="s-featureItem">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--primary))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
            <div>
                <div class="s-featureTitle">Price Match Promise</div>
                <div class="s-featureDesc">We won't be beaten</div>
            </div>
        </div>
    </div>
</div>

<!-- Categories -->
<section class="s-section">
    <div class="s-sectionHeader">
        <h2 class="s-sectionTitle">Shop Groceries</h2>
        <a href="{{ route('products.index') }}" class="s-viewAll">View all</a>
    </div>
    <div class="s-catRow">
        @foreach (\App\Models\Category::whereNull('parent_id')->where('is_enabled', true)->take(12)->get() as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="s-catCard">
                @if($cat->image)
                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="s-catImg" />
                @else
                    <div class="s-catImgPlaceholder" style="background: hsl({{ 20 * $cat->id % 360 }}, 70%, 90%); color: hsl({{ 20 * $cat->id % 360 }}, 70%, 30%);">
                        {{ substr($cat->name, 0, 1) }}
                    </div>
                @endif
                <span class="s-catLabel">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>

<!-- Featured Products -->
<section class="s-section" style="padding-top: 0;">
    <h2 class="s-sectionTitle" style="margin-bottom: 32px;">Featured Products</h2>
    <div class="s-prodGrid">
        @foreach ($latestProducts as $p)
            <div class="s-prodCard" onclick="window.location='{{ route('products.show', $p) }}'">
                <div class="s-prodImgWrap">
                    <img src="{{ $p->image ?: 'https://placehold.co/400x400/f7f5ed/1f332a?text='.urlencode($p->name) }}" alt="{{ $p->name }}" class="s-prodImg" />
                </div>
                <a href="{{ route('products.show', $p) }}" class="s-prodName">{{ $p->name }}</a>
                <div class="s-prodWeight">
                    @if($p->manage_stock) {{ $p->stock_quantity }} in stock @else In stock @endif
                </div>
                <div class="s-prodPriceRow">
                    @if($p->sale_price)
                        <span class="s-prodPrice">${{ number_format($p->sale_price, 2) }}</span>
                        <span class="s-priceOriginal">${{ number_format($p->price, 2) }}</span>
                    @else
                        <span class="s-prodPrice">${{ number_format($p->price, 2) }}</span>
                    @endif
                </div>
                <!-- Prevent button click from navigating to product detail (event bubbling issue handled simply by using a form) -->
                <form action="{{ route('cart.add', $p) }}" method="POST" style="margin: 0; margin-top: auto;" onclick="event.stopPropagation();">
                    @csrf
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="s-addBtn">Add to Cart</button>
                </form>
            </div>
        @endforeach
    </div>
</section>

<!-- Bottom Scrollable Brands/Trust Bar -->
<div class="s-marquee">
    <div class="s-marqueeContent">
        @foreach (range(1, 4) as $i) {{-- Duplicate for seamless loop --}}
            <div class="s-marqueeItem"><img src="https://placehold.co/120x40?text=NEPALI+FOOD" class="s-marqueeIcon"> AUTHENTIC PRODUCTS</div>
            <div class="s-marqueeItem"><img src="https://placehold.co/120x40?text=FRESH" class="s-marqueeIcon"> FRESH GROCERIES</div>
            <div class="s-marqueeItem"><img src="https://placehold.co/120x40?text=FREE+SHIP" class="s-marqueeIcon"> FREE DELIVERY</div>
            <div class="s-marqueeItem"><img src="https://placehold.co/120x40?text=SECURE" class="s-marqueeIcon"> SECURE PAYMENT</div>
            <div class="s-marqueeItem"><img src="https://placehold.co/120x40?text=24/7" class="s-marqueeIcon"> 24/7 SUPPORT</div>
        @endforeach
    </div>
</div>
@endsection
