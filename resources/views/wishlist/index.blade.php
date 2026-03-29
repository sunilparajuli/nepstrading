@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<style>
    .wl-header { padding: 48px 0; background: hsl(var(--muted)); text-align: center; }
    .wl-headerTitle { font-family: 'DM Serif Display', serif; font-size: 36px; color: hsl(var(--fg)); margin: 0 0 8px; }
    .wl-headerDesc { font-size: 16px; color: hsl(var(--muted-fg)); margin: 0; }
    .wl-wrap { max-width: 1200px; margin: 0 auto; padding: 32px 16px; }
    .wl-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    @media (max-width: 1024px) { .wl-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .wl-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .wl-grid { grid-template-columns: 1fr; } }
    .wl-card { background: hsl(var(--bg)); border-radius: 12px; border: 1px solid hsl(var(--border)); padding: 16px; transition: all 0.3s; position: relative; }
    .wl-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .wl-imgWrap { aspect-ratio: 1; margin-bottom: 16px; overflow: hidden; border-radius: 8px; background: hsl(var(--muted)); }
    .wl-img { width: 100%; height: 100%; object-fit: cover; }
    .wl-name { font-size: 15px; font-weight: 600; color: hsl(var(--fg)); margin: 0; text-decoration: none; }
    .wl-name:hover { color: hsl(var(--primary)); }
    .wl-price { font-size: 16px; font-weight: 700; color: hsl(var(--fg)); margin-top: 8px; }
    .wl-actions { display: flex; gap: 8px; margin-top: 12px; }
    .wl-addBtn { flex: 1; padding: 10px; font-size: 13px; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; background: hsl(var(--primary)); color: hsl(var(--primary-fg)); transition: all 0.2s; }
    .wl-addBtn:hover { background: hsl(145 63% 28%); }
    .wl-removeBtn { padding: 10px 14px; font-size: 13px; border-radius: 8px; border: 1px solid hsl(var(--border)); background: hsl(var(--bg)); cursor: pointer; color: hsl(var(--muted-fg)); transition: all 0.2s; }
    .wl-removeBtn:hover { border-color: hsl(var(--sale)); color: hsl(var(--sale)); }
    .wl-empty { text-align: center; padding: 80px 0; }
</style>

<div class="wl-header">
    <h1 class="wl-headerTitle">My Wishlist</h1>
    <p class="wl-headerDesc">{{ count($items) }} {{ \Illuminate\Support\Str::plural('item', count($items)) }} saved</p>
</div>

<div class="wl-wrap">
    @if(count($items) > 0)
    <div class="wl-grid">
        @foreach($items as $item)
        @if($item->product)
        <div class="wl-card">
            <div class="wl-imgWrap">
                <a href="{{ route('products.show', $item->product) }}">
                    <img src="{{ $item->product->image ?: 'https://placehold.co/400x400/f7f5ed/1f332a?text='.urlencode($item->product->name) }}" class="wl-img" alt="{{ $item->product->name }}">
                </a>
            </div>
            <a href="{{ route('products.show', $item->product) }}" class="wl-name">{{ $item->product->name }}</a>
            <div class="wl-price">
                @if($item->product->sale_price)
                    <span style="color: hsl(var(--sale));">${{ number_format($item->product->sale_price, 2) }}</span>
                    <span style="text-decoration: line-through; color: hsl(var(--muted-fg)); font-size: 13px;">${{ number_format($item->product->price, 2) }}</span>
                @else
                    ${{ number_format($item->product->price, 2) }}
                @endif
            </div>
            <div class="wl-actions">
                <form action="{{ route('cart.add', $item->product) }}" method="POST" style="flex:1;">
                    @csrf
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="wl-addBtn" style="width:100%;">Add to Cart</button>
                </form>
                <form action="{{ route('wishlist.destroy', $item) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="wl-removeBtn" title="Remove">✕</button>
                </form>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @else
    <div class="wl-empty">
        <h2 style="font-family: 'DM Serif Display', serif; font-size: 32px; margin-bottom: 12px;">Your wishlist is empty</h2>
        <p style="color: hsl(var(--muted-fg)); margin-bottom: 32px;">Browse products and save your favorites for later.</p>
        <a href="{{ route('products.index') }}" style="display: inline-flex; padding: 12px 24px; background: hsl(var(--primary)); color: hsl(var(--primary-fg)); border-radius: 8px; text-decoration: none; font-weight: 600;">Start Shopping</a>
    </div>
    @endif
</div>
@endsection
