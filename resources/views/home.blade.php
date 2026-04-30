@extends('layouts.app')
@section('title', 'Nepstrading - Authentic Nepali & Asian Groceries Australia')
@section('meta_description', 'Shop authentic Nepali, Indian and Asian groceries delivered across Australia.')

@section('content')
<style>
/* ─── GLOBAL ─────────────────────────────────────────── */
.hp-wrap  { max-width: 1380px; margin: 0 auto; padding: 0 20px; }
.hp-sec   { padding: 40px 0; }

/* ─── SECTION HEADER ─────────────────────────────────── */
.hp-hdr        { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 20px; }
.hp-hdr-title  { font-family: 'DM Serif Display', serif; font-size: 22px; font-weight: 700;
                 color: hsl(var(--fg)); text-transform: uppercase; letter-spacing: .04em; margin: 0; }
.hp-hdr-link   { font-size: 13px; color: hsl(var(--fg)); text-decoration: underline;
                 text-underline-offset: 3px; font-weight: 600; white-space: nowrap; }
.hp-hdr-link:hover { color: hsl(var(--primary)); }

/* ─── SLIDER SHELL ───────────────────────────────────── */
.hp-shell  { position: relative; border: 1px solid #e9e9e9; background: #fff; }
.hp-track  { display: flex; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; }
.hp-track::-webkit-scrollbar { display: none; }

/* ─── ARROW BUTTONS ──────────────────────────────────── */
.hp-arr {
    position: absolute; top: 38%; transform: translateY(-50%);
    width: 38px; height: 38px; border-radius: 50%;
    background: #fff; border: 1px solid #d1d5db;
    box-shadow: 0 2px 10px rgba(0,0,0,.12);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; z-index: 20; color: #111; font-size: 15px;
    transition: background .18s, color .18s;
}
.hp-arr:hover { background: hsl(var(--primary)); color: #fff; border-color: hsl(var(--primary)); }
.hp-arr.l { left: -19px; }
.hp-arr.r { right: -19px; }
@media(max-width:768px){ .hp-arr { display: none; } }

/* ─── PRODUCT CARD ───────────────────────────────────── */
.hp-card {
    flex: 0 0 calc(100% / 7); min-width: 158px;
    display: flex; flex-direction: column;
    border-right: 1px solid #ececec;
    background: #fff; cursor: pointer; position: relative;
    transition: box-shadow .2s;
}
.hp-card:last-child { border-right: none; }
.hp-card:hover { box-shadow: 0 0 0 2px hsl(var(--primary)) inset; z-index: 2; }
@media(max-width:1200px){ .hp-card{ flex: 0 0 20%; } }
@media(max-width:900px) { .hp-card{ flex: 0 0 33.33%; } }
@media(max-width:580px) { .hp-card{ flex: 0 0 50%; } }

/* Badge */
.hp-badge {
    position: absolute; top: 10px; left: 10px; z-index: 5;
    background: hsl(var(--primary)); color: #fff;
    font-size: 9.5px; font-weight: 700; padding: 3px 7px 3px 5px;
    border-radius: 3px; display: flex; align-items: center; gap: 4px;
    text-transform: uppercase; letter-spacing: .04em; line-height: 1.2;
}
.hp-badge svg { width: 10px; height: 10px; stroke: #fff; flex-shrink: 0; }
.hp-badge-sale {
    position: absolute; top: 10px; right: 10px; left: auto; z-index: 5;
    background: #dc2626; color: #fff;
    font-size: 9.5px; font-weight: 700; padding: 3px 7px;
    border-radius: 3px; text-transform: uppercase; letter-spacing: .04em;
}

/* Image */
.hp-img-wrap {
    width: 100%; aspect-ratio: 1;
    background: #f8f8f6;
    display: flex; align-items: center; justify-content: center;
    padding: 18px; overflow: hidden;
}
.hp-img { max-width: 100%; max-height: 100%; object-fit: contain;
          transition: transform .35s ease; }
.hp-card:hover .hp-img { transform: scale(1.06); }

/* Info */
.hp-info { padding: 11px 13px 13px; display: flex; flex-direction: column; flex: 1; }
.hp-name {
    font-size: 13.5px; font-weight: 700; color: hsl(var(--fg));
    line-height: 1.42; margin-bottom: 7px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    min-height: 38px;
}
.hp-stock { font-size: 11.5px; font-weight: 600; margin-bottom: 10px;
            display: flex; align-items: center; gap: 5px; }
.hp-dot   { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.hp-in  { color: hsl(var(--primary)); } .hp-in  .hp-dot { background: hsl(var(--primary)); }
.hp-low { color: #d97706; }             .hp-low .hp-dot { background: #d97706; }

/* Price */
.hp-price-row { margin-bottom: 13px; }
.hp-price     { font-size: 20px; font-weight: 800; color: hsl(var(--fg)); }
.hp-price-old { font-size: 12px; color: #9ca3af; text-decoration: line-through; margin-left: 6px; }

/* Add to cart */
.hp-btn {
    margin-top: auto; width: 100%;
    background: hsl(var(--primary)); color: #fff;
    border: none; padding: 10px 0;
    font-size: 11.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .07em;
    border-radius: 3px; cursor: pointer;
    transition: background .18s, transform .15s;
}
.hp-btn:hover { background: hsl(145 63% 26%); transform: translateY(-1px); }

/* ─── FEATURES BAR ───────────────────────────────────── */
.hp-feat     { background: hsl(var(--muted)); border-top: 1px solid hsl(var(--border));
               border-bottom: 1px solid hsl(var(--border)); padding: 26px 0; }
.hp-feat-in  { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; }
@media(max-width:768px){ .hp-feat-in{ grid-template-columns: repeat(2,1fr); } }
.hp-feat-item { display: flex; align-items: center; gap: 14px; }
.hp-feat-ico  { width: 42px; height: 42px; background: hsl(var(--primary)); border-radius: 50%;
                display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.hp-feat-t    { font-weight: 700; font-size: 13.5px; color: hsl(var(--fg)); margin: 0 0 1px; }
.hp-feat-d    { font-size: 11.5px; color: hsl(var(--muted-fg)); margin: 0; }

/* ─── CATEGORY ROW ───────────────────────────────────── */
.hp-cats     { display: flex; gap: 10px; overflow-x: auto; padding: 6px 2px 14px; scrollbar-width: none; }
.hp-cats::-webkit-scrollbar { display: none; }
.hp-cat      { flex: 0 0 auto; display: flex; flex-direction: column; align-items: center; gap: 9px; text-decoration: none; }
.hp-cat-circ {
    width: 96px; height: 96px; border-radius: 50%;
    background: hsl(var(--muted)); border: 1.5px solid hsl(var(--border));
    display: flex; align-items: center; justify-content: center; padding: 15px;
    transition: all .25s;
}
.hp-cat:hover .hp-cat-circ { border-color: hsl(var(--primary)); transform: translateY(-4px);
                               box-shadow: 0 10px 18px rgba(0,0,0,.08); }
.hp-cat-img  { max-width: 100%; max-height: 100%; object-fit: contain; }
.hp-cat-lbl  { font-size: 12px; font-weight: 700; color: hsl(var(--fg)); text-align: center;
               max-width: 96px; line-height: 1.3; }

/* ─── MARQUEE ────────────────────────────────────────── */
.hp-marquee  { overflow: hidden; white-space: nowrap; background: hsl(var(--fg)); padding: 15px 0; margin-top: 50px; }
.hp-m-track  { display: inline-flex; animation: mq 38s linear infinite; }
.hp-m-item   { display: inline-flex; align-items: center; gap: 9px; padding: 0 36px;
               font-size: 11.5px; font-weight: 700; color: rgba(255,255,255,.55);
               text-transform: uppercase; letter-spacing: .12em; }
@keyframes mq { to { transform: translateX(-50%); } }
</style>

@php
    $newArrivals    = \App\Models\Product::where('status','active')->latest()->take(16)->get();
    $featured       = \App\Models\Product::where('status','active')->where('is_featured',true)->inRandomOrder()->take(16)->get();
    $categories     = \App\Models\Category::whereNull('parent_id')->where('is_enabled',true)->take(16)->get();
@endphp

{{-- Hero --}}
@include('partials.home.hero')

{{-- Features --}}
<div class="hp-feat">
    <div class="hp-wrap hp-feat-in">
        <div class="hp-feat-item">
            <div class="hp-feat-ico">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </div>
            <div><p class="hp-feat-t">Free Delivery</p><p class="hp-feat-d">On orders over $99</p></div>
        </div>
        <div class="hp-feat-item">
            <div class="hp-feat-ico">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div><p class="hp-feat-t">Secure Payment</p><p class="hp-feat-d">100% protected</p></div>
        </div>
        <div class="hp-feat-item">
            <div class="hp-feat-ico">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div><p class="hp-feat-t">Fast Dispatch</p><p class="hp-feat-d">Order before 1pm</p></div>
        </div>
        <div class="hp-feat-item">
            <div class="hp-feat-ico">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div><p class="hp-feat-t">Australia Wide</p><p class="hp-feat-d">Nationwide delivery</p></div>
        </div>
    </div>
</div>

{{-- Shop By Category --}}
<section class="hp-sec">
    <div class="hp-wrap">
        <div class="hp-hdr">
            <h2 class="hp-hdr-title">Shop By Category</h2>
            <a href="{{ route('products.index') }}" class="hp-hdr-link">View all &rarr;</a>
        </div>
        <div class="hp-cats">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="hp-cat">
                <div class="hp-cat-circ">
                    @if($cat->image)
                        <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" class="hp-cat-img">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none"
                             stroke="hsl({{ ($cat->id*37)%360 }},55%,42%)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    @endif
                </div>
                <span class="hp-cat-lbl">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- New Arrivals --}}
<section class="hp-sec" style="padding-top:0">
    <div class="hp-wrap">
        <div class="hp-hdr">
            <h2 class="hp-hdr-title">New Arrivals</h2>
            <a href="{{ route('products.index') }}" class="hp-hdr-link">View all &rarr;</a>
        </div>
        <div style="position:relative">
            <button class="hp-arr l" onclick="sc('na',-1)">&#8592;</button>
            <button class="hp-arr r" onclick="sc('na', 1)">&#8594;</button>
            <div class="hp-shell">
                <div class="hp-track" id="na">
                    @foreach($newArrivals as $p)
                    <div class="hp-card" onclick="window.location='{{ route('products.show',$p) }}'">
                        @if($p->is_featured)
                        <div class="hp-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            New arrival
                        </div>
                        @endif
                        @if($p->sale_price)<div class="hp-badge-sale">Sale</div>@endif
                        <div class="hp-img-wrap">
                            <img src="{{ $p->image ?: 'https://placehold.co/280x280/f8f8f6/555?text='.urlencode($p->name) }}"
                                 alt="{{ $p->name }}" class="hp-img" loading="lazy">
                        </div>
                        <div class="hp-info">
                            <div class="hp-name">{{ $p->name }}</div>
                            @if($p->manage_stock && $p->stock_quantity <= 5)
                                <div class="hp-stock hp-low"><span class="hp-dot"></span> Low stock</div>
                            @else
                                <div class="hp-stock hp-in"><span class="hp-dot"></span> In stock</div>
                            @endif
                            <div class="hp-price-row">
                                <span class="hp-price">${{ number_format($p->sale_price ?: $p->price, 2) }}</span>
                                @if($p->sale_price)<span class="hp-price-old">${{ number_format($p->price, 2) }}</span>@endif
                            </div>
                            <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation()">
                                @csrf<input type="hidden" name="qty" value="1">
                                <button type="submit" class="hp-btn">+ Add to Cart</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Products --}}
@if($featured->count())
<section class="hp-sec" style="padding-top:0; background: hsl(var(--muted));">
    <div class="hp-wrap">
        <div class="hp-hdr">
            <h2 class="hp-hdr-title">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="hp-hdr-link">View all &rarr;</a>
        </div>
        <div style="position:relative">
            <button class="hp-arr l" onclick="sc('fp',-1)">&#8592;</button>
            <button class="hp-arr r" onclick="sc('fp', 1)">&#8594;</button>
            <div class="hp-shell">
                <div class="hp-track" id="fp">
                    @foreach($featured as $p)
                    <div class="hp-card" onclick="window.location='{{ route('products.show',$p) }}'">
                        @if($p->sale_price)<div class="hp-badge-sale">Sale</div>@endif
                        <div class="hp-img-wrap">
                            <img src="{{ $p->image ?: 'https://placehold.co/280x280/f8f8f6/555?text='.urlencode($p->name) }}"
                                 alt="{{ $p->name }}" class="hp-img" loading="lazy">
                        </div>
                        <div class="hp-info">
                            <div class="hp-name">{{ $p->name }}</div>
                            @if($p->manage_stock && $p->stock_quantity <= 5)
                                <div class="hp-stock hp-low"><span class="hp-dot"></span> Low stock</div>
                            @else
                                <div class="hp-stock hp-in"><span class="hp-dot"></span> In stock</div>
                            @endif
                            <div class="hp-price-row">
                                <span class="hp-price">${{ number_format($p->sale_price ?: $p->price, 2) }}</span>
                                @if($p->sale_price)<span class="hp-price-old">${{ number_format($p->price, 2) }}</span>@endif
                            </div>
                            <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation()">
                                @csrf<input type="hidden" name="qty" value="1">
                                <button type="submit" class="hp-btn">+ Add to Cart</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Trust Marquee --}}
<div class="hp-marquee">
    <div class="hp-m-track">
        @foreach(range(1,4) as $i)
            <span class="hp-m-item">&#9679; Authentic Products</span>
            <span class="hp-m-item">&#9679; Fresh Groceries</span>
            <span class="hp-m-item">&#9679; Free Delivery Over $99</span>
            <span class="hp-m-item">&#9679; Secure Payment</span>
            <span class="hp-m-item">&#9679; Australia Wide Shipping</span>
            <span class="hp-m-item">&#9679; 24/7 Support</span>
        @endforeach
    </div>
</div>

<script>
function sc(id, dir) {
    document.getElementById(id).scrollBy({ left: dir * 640, behavior: 'smooth' });
}
</script>
@endsection
