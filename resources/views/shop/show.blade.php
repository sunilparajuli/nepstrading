@extends('layouts.app')

@section('meta_title', $product->meta_title ?: $product->name . ' - ' . config('app.name'))
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 155))
@section('meta_keywords', $product->meta_keywords ?: $product->name . ', ' . $product->categories->pluck('name')->join(', '))
@section('og_type', 'product')
@section('og_title', $product->og_title ?: $product->meta_title ?: $product->name)
@section('og_description', $product->og_description ?: $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 155))
@section('og_image', $product->og_image ? asset($product->og_image) : ($product->image ? asset($product->image) : asset('images/og-default.jpg')))

@push('seo_schema')
@php
    $reviews = $product->approvedReviews()->with('user')->latest()->get();
    $avgRating = $reviews->avg('rating') ?? 0;
@endphp
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": {!! json_encode($product->name) !!},
  "image": "{{ $product->image ?: asset('images/default-og.jpg') }}",
  "description": {!! json_encode(\Illuminate\Support\Str::limit(strip_tags($product->description), 150)) !!},
  "sku": "{{ $product->sku }}",
  "brand": {
    "@@type": "Brand",
    "name": "{{ \App\Models\SiteSetting::getValue('app_name', 'Nepstrading') }}"
  },
  @if($reviews->count() > 0)
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ number_format($avgRating, 1) }}",
    "reviewCount": "{{ $reviews->count() }}"
  },
  "review": [
    @foreach($reviews->take(3) as $review)
    {
      "@@type": "Review",
      "reviewRating": {
        "@@type": "Rating",
        "ratingValue": "{{ $review->rating }}"
      },
      "author": {
        "@@type": "Person",
        "name": "{{ $review->user->name ?? 'Anonymous' }}"
      },
      "reviewBody": {!! json_encode(\Illuminate\Support\Str::limit($review->content, 100)) !!}
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ],
  @endif
  "offers": {
    "@@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "AUD",
    "price": "{{ $product->sale_price ?: $product->price }}",
    "priceValidUntil": "{{ now()->addMonths(6)->toDateString() }}",
    "availability": "{{ ($product->manage_stock && $product->stock_quantity <= 0) ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock' }}",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
@endpush

@section('content')
<style>
    .pd-wrap { max-width: 1200px; margin: 0 auto; padding: 48px 16px 64px; }
    .pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start; }
    @media (max-width: 768px) { .pd-grid { grid-template-columns: 1fr; gap: 32px; } }

    .pd-gallery { position: sticky; top: 32px; }
    .pd-mainImg-container {
        position: relative; width: 100%; aspect-ratio: 1; border-radius: 12px;
        border: 1px solid hsl(var(--border)); background: hsl(var(--muted)); margin-bottom: 12px;
        overflow: hidden; cursor: zoom-in;
    }
    .pd-mainImg {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.1s ease-out;
    }
    .pd-magnifier-lens {
        position: absolute; width: 150px; height: 150px; border: 2px solid #fff;
        border-radius: 50%; box-shadow: 0 0 0 100vw rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.2);
        pointer-events: none; opacity: 0; transition: opacity 0.2s;
        z-index: 10; display: none;
    }
    .pd-mainImg-container:hover .pd-magnifier-lens { opacity: 1; display: block; }
    
    .pd-thumbRow { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
    .pd-thumb {
        aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 2px solid transparent;
        cursor: pointer; padding: 0; background: hsl(var(--muted)); transition: border-color 0.2s;
    }
    .pd-thumb.active, .pd-thumb:hover { border-color: hsl(var(--primary)); }
    .pd-thumb img { width: 100%; height: 100%; object-fit: cover; }


    .pd-cat {
        font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;
        color: hsl(var(--primary)); margin-bottom: 8px;
    }
    .pd-title {
        font-family: 'DM Serif Display', serif; font-size: 32px; font-weight: 400;
        color: hsl(var(--fg)); margin: 0 0 16px 0; line-height: 1.2;
    }
    .pd-priceRow { display: flex; align-items: baseline; gap: 12px; margin-bottom: 24px; }
    .pd-price { font-size: 28px; font-weight: 700; color: hsl(var(--fg)); }
    .pd-priceOld { font-size: 18px; text-decoration: line-through; color: hsl(var(--muted-fg)); }
    .pd-priceSale { color: hsl(var(--sale)); }
    .pd-sku { font-size: 13px; color: hsl(var(--muted-fg)); margin-bottom: 24px; }

    .pd-stock {
        display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px;
        border-radius: 999px; font-size: 13px; font-weight: 600; margin-bottom: 24px;
    }
    .pd-stock.in { background: #dcfce7; color: #15803d; }
    .pd-stock.out { background: #fee2e2; color: #dc2626; }
    .pd-stockDot { width: 8px; height: 8px; border-radius: 50%; }
    .pd-stock.in .pd-stockDot { background: #22c55e; }
    .pd-stock.out .pd-stockDot { background: #ef4444; }

    .pd-qtyRow { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
    .pd-qtyControl {
        display: flex; align-items: center; gap: 0; border: 1px solid hsl(var(--border));
        border-radius: 999px; overflow: hidden; background: hsl(var(--bg));
    }
    .pd-qtyBtnRound {
        width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;
        border: none; cursor: pointer; background: transparent; color: hsl(var(--primary));
        transition: all 0.2s; font-size: 18px; font-weight: 700;
    }
    .pd-qtyBtnRound:hover { background: hsl(var(--muted)); }
    .pd-qtyInput {
        width: 44px; height: 44px; text-align: center; border: none; background: transparent;
        font-size: 16px; font-weight: 700; color: hsl(var(--fg)); outline: none; padding: 0;
    }
    /* Hide number spinners */
    .pd-qtyInput::-webkit-inner-spin-button,
    .pd-qtyInput::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .pd-qtyInput { -moz-appearance: textfield; }

    .pd-addBtn {
        flex: 1; min-width: 180px; height: 48px; border: none; border-radius: 999px;
        font-size: 15px; font-weight: 600; cursor: pointer;
        background: hsl(var(--primary)); color: hsl(var(--primary-fg));
        transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .pd-addBtn:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 12px hsla(var(--primary), 0.3); }
    .pd-addBtn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

    .pd-buyBtn {
        width: 100%; height: 48px; border: 2px solid hsl(var(--primary)); border-radius: 999px;
        font-size: 15px; font-weight: 600; cursor: pointer; margin-bottom: 32px;
        background: transparent; color: hsl(var(--primary)); transition: all 0.2s;
    }
    .pd-buyBtn:hover { background: hsl(var(--primary)); color: hsl(var(--primary-fg)); transform: translateY(-1px); box-shadow: 0 4px 12px hsla(var(--primary), 0.2); }

    .pd-accordion { border-top: 1px solid hsl(var(--border)); }
    .pd-accItem { border-bottom: 1px solid hsl(var(--border)); padding: 20px 0; }
    .pd-accTitle { font-size: 16px; font-weight: 600; color: hsl(var(--fg)); margin: 0 0 12px 0; }
    .pd-accBody { font-size: 15px; color: hsl(var(--muted-fg)); line-height: 1.7; }
    .pd-attrGrid { display: grid; grid-template-columns: 140px 1fr; gap: 8px 16px; font-size: 14px; }
    .pd-attrKey { font-weight: 500; color: hsl(var(--muted-fg)); }
    .pd-attrVal { font-weight: 500; color: hsl(var(--fg)); }

    .pd-swatches { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 4px; }
    .pd-swatch {
        width: 32px; height: 32px; border-radius: 4px; border: 1px solid hsl(var(--border));
        cursor: pointer; transition: all 0.2s; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .pd-swatch:hover, .pd-swatch.active { border-color: hsl(var(--primary)); transform: scale(1.05); }
    .pd-swatch.color { border-radius: 50%; }
    .pd-swatch.text { width: auto; height: auto; padding: 6px 14px; font-size: 13px; font-weight: 600; border-radius: 999px; }

    /* Flash Sale Styles */
    .pd-deal-box {
        background: hsl(var(--sale) / 0.05); border: 1px solid hsl(var(--sale) / 0.2);
        border-radius: 12px; padding: 16px; margin-bottom: 24px;
    }
    .pd-deal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .pd-deal-label { font-size: 14px; font-weight: 700; color: hsl(var(--sale)); text-transform: uppercase; letter-spacing: 0.05em; }
    .pd-deal-timer { display: flex; gap: 8px; font-family: monospace; font-size: 16px; font-weight: 700; color: hsl(var(--fg)); }
    .pd-deal-timer span { background: hsl(var(--bg)); border: 1px solid hsl(var(--border)); padding: 4px 6px; border-radius: 4px; min-width: 32px; text-align: center; }
    
    .pd-deal-progress-wrap { margin-top: 12px; }
    .pd-deal-stats { display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: hsl(var(--muted-fg)); }
    .pd-deal-bar { width: 100%; height: 8px; background: hsl(var(--border)); border-radius: 999px; overflow: hidden; }
    .pd-deal-fill { height: 100%; background: linear-gradient(90deg, hsl(var(--sale)), #facc15); border-radius: inherit; transition: width 0.5s ease-out; }

    /* Cart Disabled advisory */
    .pd-disabled-advisory {
        background: hsl(var(--primary) / 0.03); border: 1px solid hsl(var(--primary) / 0.15);
        border-radius: 16px; padding: 24px; margin-bottom: 32px;
        color: hsl(var(--fg)); box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        position: relative; overflow: hidden;
    }
    .pd-disabled-advisory::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
        background: hsl(var(--primary));
    }
    .pd-disabled-header { display: flex; align-items: center; gap: 10px; font-weight: 800; color: hsl(var(--fg)); margin-bottom: 12px; font-size: 16px; text-transform: uppercase; letter-spacing: 0.02em; }
    .pd-disabled-icon { color: hsl(var(--primary)); }
    .pd-disabled-body { font-size: 15px; line-height: 1.7; color: hsl(var(--muted-fg)); }
</style>

<!-- Breadcrumbs -->
<div style="border-bottom: 1px solid hsl(var(--border)); background: hsl(var(--muted));">
    <div style="max-width: 1200px; margin: 0 auto; padding: 12px 16px; display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: hsl(var(--muted-fg));">
        <a href="/" style="color: hsl(var(--muted-fg)); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='hsl(var(--primary))'" onmouseout="this.style.color='hsl(var(--muted-fg))'">Home</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <a href="{{ route('products.index') }}" style="color: hsl(var(--muted-fg)); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='hsl(var(--primary))'" onmouseout="this.style.color='hsl(var(--muted-fg))'">Products</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        <span style="color: hsl(var(--fg));">{{ $product->name }}</span>
    </div>
</div>

<div class="pd-wrap">
    <div class="pd-grid">
        
        <!-- Image Gallery -->
        <div class="pd-gallery">
            <div class="pd-mainImg-container" id="magnifierContainer">
                <img src="{{ $product->image ?: 'https://placehold.co/800x800/f7f5ed/1f332a?text='.urlencode($product->name) }}" class="pd-mainImg" id="mainImg" alt="{{ $product->name }}">
                <div class="pd-magnifier-lens" id="magnifierLens"></div>
            </div>
            
            <div class="pd-thumbRow">
                <button class="pd-thumb active">
                    <img src="{{ $product->image ?: 'https://placehold.co/400x400/f7f5ed/1f332a?text='.urlencode($product->name) }}" alt="{{ $product->name }}">
                </button>
            </div>
        </div>

        <!-- Product Info -->
        <div>
            @if($product->categories->count() > 0)
                <div class="pd-cat">{{ $product->categories->pluck('name')->join(' · ') }}</div>
            @endif
            
            <h1 class="pd-title">{{ $product->name }}</h1>
            <div class="pd-priceRow">
                @if($product->is_on_deal)
                    <span class="pd-price pd-priceSale">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="pd-priceOld">${{ number_format($product->price, 2) }}</span>
                @elseif($product->sale_price)
                    <span class="pd-price pd-priceSale">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="pd-priceOld">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="pd-price">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            @if($product->is_on_deal)
            <div class="pd-deal-box">
                <div class="pd-deal-header">
                    <div class="pd-deal-label">🔥 Flash Deal</div>
                    <div class="pd-deal-timer" id="dealTimer" data-until="{{ $product->deal_ends_at->timestamp }}">
                        <span id="timer-h">00</span>:
                        <span id="timer-m">00</span>:
                        <span id="timer-s">00</span>
                    </div>
                </div>
                <div class="pd-deal-progress-wrap">
                    <div class="pd-deal-stats">
                        <span>Sold: {{ $product->deal_sales_count }}</span>
                        <span>Total: {{ $product->deal_total_stock }}</span>
                    </div>
                    <div class="pd-deal-bar">
                        <div class="pd-deal-fill" style="width: {{ $product->deal_progress }}%"></div>
                    </div>
                </div>
            </div>
            @endif

            @if($product->sku)
                <div class="pd-sku">SKU: {{ $product->sku }}</div>
            @endif

            @if($product->manage_stock)
                @if($product->stock_quantity > 0)
                    <div class="pd-stock in">
                        <div class="pd-stockDot"></div>
                        {{ $product->stock_quantity }} in stock
                    </div>
                @else
                    <div class="pd-stock out">
                        <div class="pd-stockDot"></div>
                        Out of stock
                    </div>
                @endif
            @endif

            @if($product->allow_add_to_cart)
                <div class="pd-qtyRow">
                    <!-- Rounded Quantity Control -->
                    <div class="pd-qtyControl">
                        <button type="button" class="pd-qtyBtnRound" onclick="pdQty(-1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <input type="number" name="qty" value="1" min="1" max="{{ $product->manage_stock ? $product->stock_quantity : '' }}" class="pd-qtyInput" id="pdQtyInput" readonly>
                        <button type="button" class="pd-qtyBtnRound" onclick="pdQty(1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>

                    <!-- Add to Cart -->
                    <button type="button" class="pd-addBtn"
                            onclick="addToCart({{ $product->id }}, document.getElementById('pdQtyInput').value)"
                            {{ $product->manage_stock && $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        {{ $product->manage_stock && $product->stock_quantity <= 0 ? 'Sold Out' : 'Add to Cart' }}
                    </button>
                </div>
                
                @if(!($product->manage_stock && $product->stock_quantity <= 0))
                    <button type="button" class="pd-buyBtn" onclick="addToCart({{ $product->id }}, document.getElementById('pdQtyInput').value); setTimeout(() => window.location='/cart', 500);">Buy it now</button>
                @endif
            @else
            <div class="pd-disabled-advisory">
                <div class="pd-disabled-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pd-disabled-icon"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>Ordering Information</span>
                </div>
                <div class="pd-disabled-body">
                    {!! nl2br(e($product->cart_disabled_message ?? 'This product is currently not available for online purchase. Please visit our shop or contact us for more details.')) !!}
                </div>
            </div>
            @endif

            @auth
            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" style="margin-bottom: 24px;">
                @csrf
                @php $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
                <button type="submit" style="display: flex; align-items: center; gap: 8px; background: none; border: 1px solid hsl(var(--border)); padding: 10px 20px; border-radius: 999px; cursor: pointer; font-size: 14px; font-weight: 500; color: {{ $inWishlist ? 'hsl(var(--sale))' : 'hsl(var(--fg))' }}; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    {{ $inWishlist ? 'In Wishlist' : 'Add to Wishlist' }}
                </button>
            </form>
            @endauth

            <div class="pd-accordion">
                <div class="pd-accItem">
                    <h3 class="pd-accTitle">Description</h3>
                    <div class="pd-accBody">
                        {{ $product->description ?: 'No description available for this product.' }}
                    </div>
                </div>
                
                @if($product->attributes->count() > 0)
                <div class="pd-accItem">
                    <h3 class="pd-accTitle">Specifications</h3>
                    <div class="pd-attrDetails">
                        @php 
                            $groupedAttributes = $product->attributes->sortBy(function($term) {
                                return optional($term->attribute)->weight ?? 0;
                            })->groupBy(function($term) {
                                return optional($term->attribute)->name ?? 'Other';
                            });
                        @endphp
                        
                        @foreach($groupedAttributes as $attrName => $terms)
                            @php $terms = $terms->sortBy('weight'); @endphp
                            <div style="margin-bottom: 20px;">
                                <label class="pd-attrKey" style="display: block; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">{{ $attrName }}</label>
                                <div class="pd-swatches">
                                    @foreach($terms as $term)
                                        @if(optional($term->attribute)->type === 'color')
                                            <div class="pd-swatch color" style="background-color: {{ $term->value }};" title="{{ $term->name }}"></div>
                                        @elseif(optional($term->attribute)->type === 'image')
                                            <div class="pd-swatch image" title="{{ $term->name }}">
                                                <img src="{{ $term->value }}" alt="{{ $term->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="pd-swatch text">{{ $term->name }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="pd-accItem">
                    <h3 class="pd-accTitle">Shipping & Returns</h3>
                    <div class="pd-accBody">
                        Free delivery on orders over $50. Returns accepted within 14 days of delivery.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Similar Products --}}
@php
    $similarProducts = \App\Models\Product::where('status', 'active')
        ->where('id', '!=', $product->id)
        ->whereHas('categories', function($q) use ($product) {
            $q->whereIn('categories.id', $product->categories->pluck('id'));
        })
        ->inRandomOrder()
        ->take(4)
        ->get();
@endphp

@if($similarProducts->count() > 0)
<section style="max-width: 1200px; margin: 0 auto; padding: 0 16px 48px;">
    <h2 style="font-family: 'DM Serif Display', serif; font-size: 24px; color: hsl(var(--fg)); margin-bottom: 32px; border-top: 1px solid hsl(var(--border)); padding-top: 48px;">You May Also Like</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px;">
        @foreach($similarProducts as $p)
            @php
                $price = $p->sale_price ?: $p->price;
                $parts = explode('.', number_format($price, 2));
            @endphp
            <div class="hp-card" onclick="window.location='{{ route('products.show', $p) }}'" style="flex: none; min-width: 0; border: 1px solid hsl(var(--border)); border-radius: 12px; overflow: hidden; background: #fff;">
                <div class="hp-img-wrap" style="background: #f7f7f5;">
                    <img src="{{ $p->image ?: 'https://placehold.co/400x400/f7f7f5/1a3c34?text='.urlencode($p->name) }}" alt="{{ $p->name }}" class="hp-img" loading="lazy" />
                </div>
                <div class="hp-content" style="padding: 16px;">
                    <h3 class="hp-name" style="font-size: 15px; font-weight: 700; color: var(--mahal-text); margin-bottom: 8px; line-height: 1.4; height: 42px; overflow: hidden;">{{ $p->name }}</h3>
                    <div class="hp-price-row" style="margin-bottom: 16px; display: flex; align-items: baseline;">
                        <span class="hp-currency" style="font-size: 14px; font-weight: 800;">$</span>
                        <span class="hp-price-main" style="font-size: 22px; font-weight: 800; color: var(--mahal-text);">{{ $parts[0] }}</span>
                        <sup class="hp-price-cents" style="font-size: 12px; font-weight: 800;">{{ $parts[1] }}</sup>
                        @if($p->sale_price)
                            <span class="hp-price-old" style="font-size: 14px; color: var(--mahal-muted); text-decoration: line-through; margin-left: 8px;">${{ number_format($p->price, 2) }}</span>
                        @endif
                    </div>
                    <form action="{{ route('cart.add', $p) }}" method="POST" onclick="event.stopPropagation();">
                        @csrf
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="hp-add-btn" style="width: 100%; background: var(--mahal-green); color: #fff; border: none; padding: 10px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer;">+ Add to Cart</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- Reviews Section -->
<style>
    .rv-section { max-width: 1200px; margin: 0 auto; padding: 0 16px 64px; }
    .rv-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; padding-top: 32px; border-top: 1px solid hsl(var(--border)); }
    .rv-title { font-family: 'DM Serif Display', serif; font-size: 24px; color: hsl(var(--fg)); margin: 0; }
    .rv-summary { display: flex; align-items: center; gap: 12px; }
    .rv-avgScore { font-size: 32px; font-weight: 700; color: hsl(var(--fg)); }
    .rv-stars { color: #f59e0b; font-size: 18px; letter-spacing: 2px; }
    .rv-count { font-size: 14px; color: hsl(var(--muted-fg)); }
    .rv-list { display: flex; flex-direction: column; gap: 24px; margin-bottom: 40px; }
    .rv-item { background: hsl(var(--muted)); border-radius: 12px; padding: 20px; }
    .rv-itemHeader { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .rv-avatar { width: 36px; height: 36px; border-radius: 50%; background: hsl(var(--primary)); color: hsl(var(--primary-fg)); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }
    .rv-author { font-weight: 600; font-size: 14px; color: hsl(var(--fg)); }
    .rv-verified { font-size: 11px; background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 999px; font-weight: 600; }
    .rv-date { font-size: 12px; color: hsl(var(--muted-fg)); margin-left: auto; }
    .rv-itemStars { color: #f59e0b; font-size: 14px; margin-bottom: 8px; }
    .rv-itemTitle { font-weight: 600; font-size: 15px; color: hsl(var(--fg)); margin-bottom: 4px; }
    .rv-itemContent { font-size: 14px; color: hsl(var(--muted-fg)); line-height: 1.6; }
    .rv-form { background: hsl(var(--muted)); border-radius: 12px; padding: 24px; }
    .rv-formTitle { font-size: 18px; font-weight: 600; color: hsl(var(--fg)); margin: 0 0 20px; }
    .rv-field { margin-bottom: 16px; }
    .rv-label { display: block; font-size: 13px; font-weight: 600; color: hsl(var(--fg)); margin-bottom: 6px; }
    .rv-input, .rv-textarea { width: 100%; padding: 10px 14px; border: 1px solid hsl(var(--border)); border-radius: 8px; font-size: 14px; background: hsl(var(--bg)); color: hsl(var(--fg)); outline: none; font-family: inherit; box-sizing: border-box; }
    .rv-textarea { min-height: 100px; resize: vertical; }
    .rv-input:focus, .rv-textarea:focus { border-color: hsl(var(--primary)); }
    .rv-starPicker { display: flex; gap: 4px; flex-direction: row-reverse; justify-content: flex-end; }
    .rv-starPicker input { display: none; }
    .rv-starPicker label { font-size: 28px; color: hsl(var(--border)); cursor: pointer; transition: color 0.15s; }
    .rv-starPicker label:hover, .rv-starPicker label:hover ~ label,
    .rv-starPicker input:checked ~ label { color: #f59e0b; }
    .rv-submitBtn { padding: 12px 32px; background: hsl(var(--primary)); color: hsl(var(--primary-fg)); border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .rv-submitBtn:hover { background: hsl(145 63% 28%); }
    .rv-empty { text-align: center; padding: 32px; color: hsl(var(--muted-fg)); font-size: 14px; }
</style>

<div class="rv-section">
    @php
        $reviews = $product->approvedReviews()->with('user')->latest()->get();
        $avgRating = $reviews->avg('rating') ?? 0;
    @endphp

    <div class="rv-header">
        <h2 class="rv-title">Customer Reviews</h2>
        @if($reviews->count() > 0)
        <div class="rv-summary">
            <span class="rv-avgScore">{{ number_format($avgRating, 1) }}</span>
            <div>
                <div class="rv-stars">{{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5 - round($avgRating)) }}</div>
                <div class="rv-count">{{ $reviews->count() }} {{ \Illuminate\Support\Str::plural('review', $reviews->count()) }}</div>
            </div>
        </div>
        @endif
    </div>

    @if($reviews->count() > 0)
    <div class="rv-list">
        @foreach($reviews as $review)
        <div class="rv-item">
            <div class="rv-itemHeader">
                <div class="rv-avatar">{{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}</div>
                <span class="rv-author">{{ $review->user->name ?? 'Anonymous' }}</span>
                @if($review->verified_purchase)
                    <span class="rv-verified">Verified Purchase</span>
                @endif
                <span class="rv-date">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            <div class="rv-itemStars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
            @if($review->title)
                <div class="rv-itemTitle">{{ $review->title }}</div>
            @endif
            @if($review->content)
                <div class="rv-itemContent">{{ $review->content }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="rv-empty">No reviews yet. Be the first to review this product!</div>
    @endif

    @auth
        @php $existingReview = \App\Models\Review::where('user_id', auth()->id())->where('product_id', $product->id)->exists(); @endphp
        @if(!$existingReview)
        <div class="rv-form">
            <h3 class="rv-formTitle">Write a Review</h3>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                <div class="rv-field">
                    <label class="rv-label">Rating</label>
                    <div class="rv-starPicker">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ $i === 5 ? 'required' : '' }}>
                            <label for="star{{ $i }}">★</label>
                        @endfor
                    </div>
                </div>
                <div class="rv-field">
                    <label class="rv-label" for="rv-title">Title</label>
                    <input type="text" name="title" id="rv-title" class="rv-input" placeholder="Sum it up in a few words">
                </div>
                <div class="rv-field">
                    <label class="rv-label" for="rv-content">Your Review</label>
                    <textarea name="content" id="rv-content" class="rv-textarea" placeholder="What did you think about this product?"></textarea>
                </div>
                <button type="submit" class="rv-submitBtn">Submit Review</button>
            </form>
        </div>
        @else
        <p style="text-align: center; padding: 16px; color: hsl(var(--muted-fg)); font-size: 14px;">You've already reviewed this product.</p>
        @endif
    @else
        <p style="text-align: center; padding: 16px; color: hsl(var(--muted-fg)); font-size: 14px;"><a href="{{ route('login') }}" style="color: hsl(var(--primary)); font-weight: 600;">Log in</a> to write a review.</p>
    @endauth
</div>


<script>
function pdQty(delta) {
    const input = document.getElementById('pdQtyInput');
    let val = parseInt(input.value) + delta;
    const max = input.getAttribute('max');
    if (val < 1) val = 1;
    if (max && val > parseInt(max)) val = parseInt(max);
    input.value = val;
}

const timerEl = document.getElementById('dealTimer');
if (timerEl) {
    const until = parseInt(timerEl.dataset.until);
    function updateTimer() {
        const now = Math.floor(Date.now() / 1000);
        const diff = until - now;
        if (diff <= 0) {
            timerEl.innerHTML = "DEAL ENDED";
            return;
        }
        const h = Math.floor(diff / 3600);
        const m = Math.floor((diff % 3600) / 60);
        const s = diff % 60;
        document.getElementById('timer-h').innerText = String(h).padStart(2, '0');
        document.getElementById('timer-m').innerText = String(m).padStart(2, '0');
        document.getElementById('timer-s').innerText = String(s).padStart(2, '0');
    }
    updateTimer();
    setInterval(updateTimer, 1000);
}

// Magnifier Logic
(function() {
    const container = document.getElementById('magnifierContainer');
    const mainImg = document.getElementById('mainImg');
    const lens = document.getElementById('magnifierLens');

    if (container && mainImg && lens) {
        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Calculate lens position
            let lensX = x - lens.offsetWidth / 2;
            let lensY = y - lens.offsetHeight / 2;

            // Boundary checks
            if (lensX < 0) lensX = 0;
            if (lensY < 0) lensY = 0;
            if (lensX > container.offsetWidth - lens.offsetWidth) lensX = container.offsetWidth - lens.offsetWidth;
            if (lensY > container.offsetHeight - lens.offsetHeight) lensY = container.offsetHeight - lens.offsetHeight;

            lens.style.left = lensX + 'px';
            lens.style.top = lensY + 'px';

            // Calculate zoom (2.5x zoom)
            const zoom = 2.5;
            const zX = (x / container.offsetWidth) * 100;
            const zY = (y / container.offsetHeight) * 100;

            mainImg.style.transformOrigin = `${zX}% ${zY}%`;
            mainImg.style.transform = `scale(${zoom})`;
        });

        container.addEventListener('mouseleave', () => {
            mainImg.style.transform = 'scale(1)';
            mainImg.style.transformOrigin = 'center center';
        });
    }
})();
</script>
@endsection
