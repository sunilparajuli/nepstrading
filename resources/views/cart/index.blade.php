@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<style>
    .ct-header { padding: 48px 0; background: hsl(var(--muted)); text-align: center; }
    .ct-headerTitle { font-family: 'DM Serif Display', serif; font-size: 36px; color: hsl(var(--fg)); margin: 0 0 8px; }
    .ct-headerDesc { font-size: 16px; color: hsl(var(--muted-fg)); margin: 0; }

    .ct-wrap { max-width: 1200px; margin: 0 auto; padding: 32px 16px; display: flex; gap: 32px; align-items: flex-start; }
    @media (max-width: 1024px) { .ct-wrap { flex-direction: column; } .ct-side { width: 100% !important; } }

    .ct-main { flex: 1; min-width: 0; }
    .ct-itemList { display: flex; flex-direction: column; gap: 20px; }
    .ct-itemCard {
        background: hsl(var(--bg)); border-radius: 12px; border: 1px solid hsl(var(--border));
        padding: 20px; display: flex; gap: 20px; align-items: center; position: relative;
    }
    .ct-itemImgWrap { width: 100px; height: 100px; border-radius: 8px; overflow: hidden; background: hsl(var(--muted)); flex-shrink: 0; border: 1px solid hsl(var(--border)); }
    .ct-itemImg { width: 100%; height: 100%; object-fit: cover; }
    
    .ct-itemInfo { flex: 1; min-width: 0; }
    .ct-itemName { font-size: 16px; font-weight: 600; color: hsl(var(--fg)); text-decoration: none; display: block; margin-bottom: 4px; }
    .ct-itemName:hover { color: hsl(var(--primary)); }
    .ct-itemPrice { font-size: 14px; color: hsl(var(--muted-fg)); }

    .ct-itemQty { display: flex; align-items: center; gap: 12px; }
    .ct-qtyBtn {
        width: 32px; height: 32px; border-radius: 50%; border: 1px solid hsl(var(--border));
        background: hsl(var(--bg)); color: hsl(var(--fg)); display: flex; align-items: center;
        justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 16px;
    }
    .ct-qtyBtn:hover { border-color: hsl(var(--primary)); color: hsl(var(--primary)); }
    .ct-qtyVal { font-size: 14px; font-weight: 600; min-width: 24px; text-align: center; }

    .ct-itemTotal { font-size: 16px; font-weight: 700; color: hsl(var(--fg)); min-width: 100px; text-align: right; }
    .ct-removeBtn {
        position: absolute; top: 12px; right: 12px; width: 24px; height: 24px;
        display: flex; align-items: center; justify-content: center; color: hsl(var(--muted-fg));
        cursor: pointer; transition: color 0.2s; border: none; background: none; font-size: 18px;
    }
    .ct-removeBtn:hover { color: hsl(var(--sale)); }

    .ct-side { width: 360px; flex-shrink: 0; display: flex; flex-direction: column; gap: 24px; position: sticky; top: 32px; }
    .ct-panel { background: hsl(var(--bg)); border-radius: 12px; border: 1px solid hsl(var(--border)); padding: 24px; }
    .ct-panelTitle { font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: hsl(var(--fg)); margin: 0 0 20px; padding-bottom: 12px; border-bottom: 1px solid hsl(var(--border)); }

    .ct-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
    .ct-label { color: hsl(var(--muted-fg)); }
    .ct-price { font-weight: 600; color: hsl(var(--fg)); }
    .ct-totalRow { border-top: 1px solid hsl(var(--border)); margin-top: 20px; padding-top: 20px; }
    .ct-totalLabel { font-size: 16px; font-weight: 700; color: hsl(var(--fg)); }
    .ct-totalPrice { font-size: 24px; font-weight: 800; color: hsl(var(--sale)); }

    .ct-checkoutBtn {
        width: 100%; margin-top: 24px; padding: 16px; background: hsl(var(--primary)); color: hsl(var(--primary-fg));
        border: none; border-radius: 8px; font-size: 15px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.05em; cursor: pointer; transition: all 0.2s; text-decoration: none; display: block; text-align: center;
    }
    .ct-checkoutBtn:hover { background: hsl(145 63% 28%); transform: translateY(-1px); box-shadow: 0 4px 12px hsla(145, 63%, 32%, 0.2); }

    .ct-calcIcon { width: 14px; height: 14px; margin-right: 6px; }
    .ct-detectLink { font-size: 12px; font-weight: 600; color: hsl(var(--primary)); text-decoration: none; display: flex; align-items: center; margin-bottom: 16px; }
    .ct-detectLink:hover { text-decoration: underline; }
    .ct-currentLoc { font-size: 11px; color: hsl(var(--muted-fg)); font-style: italic; margin: -12px 0 16px; }

    .ct-formGroup { margin-bottom: 12px; }
    .ct-formLabel { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; color: hsl(var(--muted-fg)); margin-bottom: 4px; }
    .ct-select, .ct-input {
        width: 100%; padding: 10px; border: 1px solid hsl(var(--border)); border-radius: 6px;
        background: hsl(var(--bg)); font-size: 13px; color: hsl(var(--fg)); outline: none;
    }
    .ct-select:focus, .ct-input:focus { border-color: hsl(var(--primary)); }
    .ct-updateBtn {
        width: 100%; margin-top: 8px; padding: 10px; background: hsl(var(--muted)); color: hsl(var(--fg));
        border: 1px solid hsl(var(--border)); border-radius: 6px; font-size: 12px; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }
    .ct-updateBtn:hover { background: hsl(var(--border)); }

    .ct-empty { text-align: center; padding: 80px 0; }
    .ct-emptyTitle { font-family: 'DM Serif Display', serif; font-size: 32px; margin-bottom: 12px; }
    .ct-emptyDesc { color: hsl(var(--muted-fg)); margin-bottom: 32px; }
    .ct-backBtn {
        display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px;
        background: hsl(var(--primary)); color: hsl(var(--primary-fg)); border-radius: 8px;
        text-decoration: none; font-weight: 600; transition: all 0.2s;
    }
    .ct-backBtn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px hsla(145, 63%, 32%, 0.2); }
</style>

<!-- Page Header -->
<div class="ct-header">
    <h1 class="ct-headerTitle">Shopping Cart</h1>
    <p class="ct-headerDesc">Review your items and select delivery options.</p>
</div>

@if(session('success'))
    <div style="max-width: 1200px; margin: 24px auto 0; padding: 0 16px;">
        <div style="background: hsl(142 71% 95%); border: 1px solid hsl(142 71% 85%); color: hsl(142 71% 25%); padding: 16px; border-radius: 8px; font-size: 14px;">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="ct-wrap">
    @if(count($cart) > 0)
        <!-- Cart Main -->
        <div class="ct-main">
            <div class="ct-itemList">
                @foreach($cart as $id => $details)
                <div class="ct-itemCard">
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="ct-removeBtn">×</button>
                    </form>

                    <div class="ct-itemImgWrap">
                        <img src="{{ $details['image'] }}" class="ct-itemImg" alt="{{ $details['name'] }}">
                    </div>

                    <div class="ct-itemInfo">
                        <a href="#" class="ct-itemName">{{ $details['name'] }}</a>
                        <div class="ct-itemPrice">Unit Price: ${{ number_format($details['price'], 2) }}</div>
                    </div>

                    <div class="ct-itemQty">
                        <form action="{{ route('cart.update', $id) }}" method="POST" class="ct-itemQty">
                            @csrf @method('PATCH')
                            <button type="button" class="ct-qtyBtn" onclick="updateQty(this, -1)">-</button>
                            <span class="ct-qtyVal">{{ $details['qty'] }}</span>
                            <input type="hidden" name="qty" value="{{ $details['qty'] }}">
                            <button type="button" class="ct-qtyBtn" onclick="updateQty(this, 1)">+</button>
                        </form>
                    </div>

                    <div class="ct-itemTotal">
                        ${{ number_format($details['price'] * $details['qty'], 2) }}
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top: 32px;">
                <a href="/products" style="color: hsl(var(--muted-fg)); text-decoration: none; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <span>←</span> Keep Shopping
                </a>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <aside class="ct-side">
            <!-- Order Summary -->
            <div class="ct-panel">
                <h3 class="ct-panelTitle">Order Summary</h3>
                <div class="ct-row">
                    <span class="ct-label">Subtotal</span>
                    <span class="ct-price">${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="ct-row">
                    <span class="ct-label">Shipping</span>
                    <span class="ct-price">{{ $shipping['name'] }}</span>
                </div>
                @if($shipping['cost'] > 0)
                <div class="ct-row">
                    <span class="ct-label">Shipping Cost</span>
                    <span class="ct-price">${{ number_format($shipping['cost'], 2) }}</span>
                </div>
                @endif
                @if(session('coupon'))
                <div class="ct-row">
                    <span class="ct-label">Coupon ({{ session('coupon.code') }})</span>
                    <span class="ct-price" style="color: hsl(var(--sale));">
                        -${{ number_format(session('coupon.type') === 'percentage' ? $subtotal * (session('coupon.amount') / 100) : min(session('coupon.amount'), $subtotal), 2) }}
                        <form action="{{ route('cart.coupon.remove') }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: hsl(var(--muted-fg)); font-size: 11px; background: none; border: none; cursor: pointer; text-decoration: underline;">Remove</button>
                        </form>
                    </span>
                </div>
                @endif
                <div class="ct-row ct-totalRow">
                    <span class="ct-totalLabel">Total</span>
                    <span class="ct-totalPrice">${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="ct-checkoutBtn">Checkout Now</a>

                @if(!session('coupon'))
                <form action="{{ route('cart.coupon.apply') }}" method="POST" style="margin-top: 16px; display: flex; gap: 8px;">
                    @csrf
                    <input type="text" name="coupon_code" placeholder="Coupon code" class="ct-input" style="flex:1; text-transform: uppercase;">
                    <button type="submit" class="ct-updateBtn" style="width: auto; margin-top: 0; padding: 10px 16px;">Apply</button>
                </form>
                @endif

                <p style="text-align: center; font-size: 11px; color: hsl(var(--muted-fg)); margin-top: 16px;">Tax calculated at checkout</p>
            </div>

            <!-- Shipping Calculator -->
            <div class="ct-panel">
                <h3 class="ct-panelTitle">Shipping Calculator</h3>
                
                <a href="{{ route('cart.shipping.auto') }}" class="ct-detectLink">
                    <svg class="ct-calcIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                    Use my current location
                </a>
                
                @if(session('shipping_location'))
                <p class="ct-currentLoc">Delivering to {{ session('shipping_location')['state'] }}, {{ session('shipping_location')['country'] }}</p>
                @endif

                <form action="{{ route('cart.shipping.set') }}" method="POST">
                    @csrf
                    <div class="ct-formGroup">
                        <label class="ct-formLabel">State / Region</label>
                        <select name="state" class="ct-select">
                            @foreach(['NSW' => 'New South Wales', 'VIC' => 'Victoria', 'QLD' => 'Queensland', 'WA' => 'Western Australia', 'SA' => 'South Australia', 'TAS' => 'Tasmania', 'ACT' => 'ACT', 'NT' => 'Northern Territory'] as $code => $name)
                                <option value="{{ $code }}" {{ (session('shipping_location')['state'] ?? '') == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ct-formGroup">
                        <label class="ct-formLabel">Country</label>
                        <select name="country" class="ct-select">
                            <option value="AU">Australia</option>
                        </select>
                    </div>
                    <div class="ct-formGroup">
                        <label class="ct-formLabel">Postcode</label>
                        <input type="text" name="postcode" value="{{ session('shipping_location')['postcode'] ?? '' }}" class="ct-input" placeholder="e.g. 2000">
                    </div>
                    <button type="submit" class="ct-updateBtn">Update Shipping</button>
                </form>
            </div>
        </aside>
    @else
        <div class="ct-main ct-empty" style="flex: 1;">
            <h2 class="ct-emptyTitle">Your cart is empty</h2>
            <p class="ct-emptyDesc">Looks like you haven't added anything to your cart yet.</p>
            <a href="/products" class="ct-backBtn">Start Shopping</a>
        </div>
    @endif
</div>

<script>
    function updateQty(btn, change) {
        const form = btn.closest('form');
        const span = form.querySelector('.ct-qtyVal');
        const input = form.querySelector('input[name="qty"]');
        let val = parseInt(span.textContent) + change;
        if (val < 1) val = 1;
        span.textContent = val;
        input.value = val;
        
        // Auto-submit after a short delay
        clearTimeout(window.qtyTimer);
        window.qtyTimer = setTimeout(() => {
            form.submit();
        }, 500);
    }
</script>
@endsection
