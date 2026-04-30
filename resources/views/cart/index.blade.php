@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<style>
    .ct-header { padding: 48px 0; background: hsl(var(--muted)); text-align: center; }
    .ct-headerTitle { font-family: 'DM Serif Display', serif; font-size: 36px; color: hsl(var(--fg)); margin: 0 0 8px; }
    .ct-headerDesc { font-size: 16px; color: hsl(var(--muted-fg)); margin: 0; }

    .ct-wrap { max-width: 1200px; margin: 0 auto; padding: 32px 16px; display: flex; gap: 32px; align-items: flex-start; position: relative; }
    @media (max-width: 1024px) { .ct-wrap { flex-direction: column; } .ct-side { width: 100% !important; } }

    .ct-main { flex: 1; min-width: 0; }
    .ct-itemList { display: flex; flex-direction: column; gap: 20px; }
    .ct-itemCard {
        background: hsl(var(--bg)); border-radius: 12px; border: 1px solid hsl(var(--border));
        padding: 20px; display: flex; gap: 20px; align-items: center; position: relative;
        transition: opacity 0.2s;
    }
    .ct-itemCard.loading { opacity: 0.5; pointer-events: none; }
    
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
    .ct-panel { background: hsl(var(--bg)); border-radius: 12px; border: 1px solid hsl(var(--border)); padding: 24px; position: relative; }
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

    /* Loading Overlay */
    .ct-overlay {
        position: absolute; inset: 0; background: rgba(255,255,255,0.7);
        display: none; align-items: center; justify-content: center; z-index: 10;
        border-radius: inherit;
    }
    .ct-panel.loading .ct-overlay { display: flex; }
    
    /* Specific Shipping Style */
    .ct-panel-shipping {
        background: hsl(var(--muted));
        border-color: hsl(var(--border) / 0.5);
    }
    .ct-panel-shipping .ct-formLabel { color: hsl(var(--fg) / 0.8); }

    .ct-spinner {
        width: 24px; height: 24px; border: 3px solid hsl(var(--border));
        border-top-color: hsl(var(--primary)); border-radius: 50%;
        animation: ct-spin 0.8s linear infinite;
    }
    @keyframes ct-spin { to { transform: rotate(360deg); } }
</style>

<!-- Page Header -->
<div class="ct-header">
    <h1 class="ct-headerTitle">Shopping Cart</h1>
    <p class="ct-headerDesc">Review your items and select delivery options.</p>
</div>

<div id="cart-content-wrapper" class="ct-wrap">
    @if(count($cart) > 0)
        <!-- Cart Main -->
        <div class="ct-main">
            <div class="ct-itemList">
                @foreach($cart as $id => $details)
                <div class="ct-itemCard" id="item-card-{{ $id }}">
                    <div class="ct-overlay"><div class="ct-spinner"></div></div>
                    <button type="button" class="ct-removeBtn" onclick="removeCartItemAJAX('{{ $id }}')">×</button>

                    <div class="ct-itemImgWrap">
                        <img src="{{ $details['image'] }}" class="ct-itemImg" alt="{{ $details['name'] }}">
                    </div>

                    <div class="ct-itemInfo">
                        <a href="#" class="ct-itemName">{{ $details['name'] }}</a>
                        <div class="ct-itemPrice">Unit Price: ${{ number_format($details['price'], 2) }}</div>
                    </div>

                    <div class="ct-itemQty">
                        <button type="button" class="ct-qtyBtn" onclick="updateQtyAJAX('{{ $id }}', -1)">-</button>
                        <span class="ct-qtyVal" id="item-qty-{{ $id }}">{{ $details['qty'] }}</span>
                        <button type="button" class="ct-qtyBtn" onclick="updateQtyAJAX('{{ $id }}', 1)">+</button>
                    </div>

                    <div class="ct-itemTotal" id="item-total-{{ $id }}">
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
            <div class="ct-panel" id="summary-panel">
                <div class="ct-overlay"><div class="ct-spinner"></div></div>
                <h3 class="ct-panelTitle">Order Summary</h3>
                <div class="ct-row">
                    <span class="ct-label">Subtotal</span>
                    <span class="ct-price" id="summary-subtotal">${{ $formatted_subtotal }}</span>
                </div>
                <div class="ct-row">
                    <span class="ct-label">Shipping</span>
                    <span class="ct-price" id="summary-shipping-name">{{ $shipping['name'] }}</span>
                </div>
                <div class="ct-row" id="shipping-cost-row" style="{{ $shipping['cost'] > 0 ? '' : 'display:none;' }}">
                    <span class="ct-label">Shipping Cost</span>
                    <span class="ct-price" id="summary-shipping-cost">${{ $formatted_shipping_cost }}</span>
                </div>
                
                <div class="ct-row ct-totalRow">
                    <span class="ct-totalLabel">Total</span>
                    <span class="ct-totalPrice" id="summary-total">${{ $formatted_total }}</span>
                </div>

                <div id="min-order-section" style="{{ !$minOrderMet ? '' : 'display:none;' }}">
                    <div style="margin-top: 16px; padding: 12px; background: hsl(0 84% 97%); border: 1px solid hsl(0 84% 90%); border-radius: 8px; color: hsl(0 84% 40%); font-size: 13px; font-weight: 500; line-height: 1.4;">
                        <span style="font-weight: 800; display: block; margin-bottom: 2px;">Minimum Order: $69.00</span>
                        You must have an order with a minimum of $69.00 to place your order. Your current order total is $<span id="min-order-current-total">{{ $formatted_subtotal }}</span>.
                    </div>
                    <button disabled class="ct-checkoutBtn" style="background: hsl(var(--muted)); color: hsl(var(--muted-fg)); cursor: not-allowed; opacity: 0.7;">Checkout Now</button>
                    <p style="text-align: center; font-size: 11px; color: hsl(var(--muted-fg)); margin-top: 8px;">Add $<span id="min-order-gap">{{ $min_order_gap }}</span> more to checkout</p>
                </div>

                <div id="checkout-button-section" style="{{ $minOrderMet ? '' : 'display:none;' }}">
                    <a href="{{ route('checkout.index') }}" class="ct-checkoutBtn">Checkout Now</a>
                </div>

                <p style="text-align: center; font-size: 11px; color: hsl(var(--muted-fg)); margin-top: 16px;">Tax calculated at checkout</p>
            </div>

            <!-- Shipping Calculator -->
            <div class="ct-panel ct-panel-shipping" id="shipping-panel">
                <div class="ct-overlay"><div class="ct-spinner"></div></div>
                <h3 class="ct-panelTitle">Shipping Calculator</h3>
                
                <a href="{{ route('cart.shipping.auto') }}" class="ct-detectLink">
                    <svg class="ct-calcIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                    Use my current location
                </a>
                
                <p id="current-location-text" class="ct-currentLoc" style="{{ session('shipping_location') ? '' : 'display:none;' }}">
                    Delivering to <span id="loc-state">{{ session('shipping_location')['state'] ?? '' }}</span>, <span id="loc-country">{{ session('shipping_location')['country'] ?? '' }}</span>
                </p>

                <form id="shipping-form" onsubmit="updateShippingAJAX(event)">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div class="ct-formGroup" style="margin-bottom: 0;">
                            <label class="ct-formLabel">State</label>
                            @php $selState = is_array(session('shipping_location')) ? (session('shipping_location')['state'] ?? '') : ''; @endphp
                            <select name="state" id="state-selector" class="ct-select" onchange="filterCities()">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->code }}" data-id="{{ $state->id }}" {{ $selState == $state->code ? 'selected' : '' }}>{{ $state->name }} ({{ $state->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ct-formGroup" style="margin-bottom: 0;">
                            <label class="ct-formLabel">City/Suburb</label>
                            @php $selCity = is_array(session('shipping_location')) ? (session('shipping_location')['city'] ?? '') : ''; @endphp
                            <select name="city" id="city-selector" class="ct-select" onchange="filterPostcodes()">
                                <option value="">Select City</option>
                                {{-- populated by JS --}}
                            </select>
                        </div>
                    </div>
                    <div class="ct-formGroup">
                        <label class="ct-formLabel">Postcode</label>
                        @php $selPostcode = is_array(session('shipping_location')) ? (session('shipping_location')['postcode'] ?? '') : ''; @endphp
                        <select name="postcode" id="postcode-selector" class="ct-select">
                            <option value="">Select Postcode</option>
                            @foreach($postcodes as $pc)
                                <option value="{{ $pc }}" {{ $selPostcode == $pc ? 'selected' : '' }}>{{ $pc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ct-formGroup">
                        <label class="ct-formLabel">Country</label>
                        <select name="country" class="ct-select">
                            <option value="AU">Australia</option>
                        </select>
                    </div>
                    <button type="submit" class="ct-updateBtn">Update Shipping</button>
                </form>
            </div>

            <!-- Discount Coupon -->
            <div class="ct-panel" id="coupon-panel">
                <style>
                    #coupon-form-toggle { cursor: pointer; display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 5; }
                    #coupon-form-toggle:hover { color: hsl(var(--primary)); }
                    #coupon-form-body { display: none; margin-top: 16px; border-top: 1px solid hsl(var(--border)); padding-top: 16px; }
                    #coupon-form-body.open { display: block; }
                    .ct-chevron { width: 12px; height: 12px; transition: transform 0.2s; }
                    #coupon-panel.open .ct-chevron { transform: rotate(180deg); }
                </style>
                
                <div id="coupon-form-toggle" onclick="const p = document.getElementById('coupon-panel'); p.classList.toggle('open'); document.getElementById('coupon-form-body').classList.toggle('open')">
                    <h3 class="ct-panelTitle" style="margin: 0; border: none; padding: 0;">Discount Coupon</h3>
                    <svg class="ct-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>

                <div id="coupon-form-body" class="{{ session('coupon') ? 'open' : '' }}">
                    @if(session('coupon'))
                    <div class="ct-row" style="margin-bottom: 0;">
                        <span class="ct-label">Applied: <strong>{{ session('coupon.code') }}</strong></span>
                        <form action="{{ route('cart.coupon.remove') }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: hsl(var(--sale)); font-size: 11px; background: none; border: none; cursor: pointer; text-decoration: underline;">Remove Coupon</button>
                        </form>
                    </div>
                    @else
                    <form action="{{ route('cart.coupon.apply') }}" method="POST">
                        @csrf
                        <div class="ct-formGroup">
                            <label class="ct-formLabel" style="margin-bottom: 8px;">Coupon Code</label>
                            <div style="display: flex; gap: 8px;">
                                <input type="text" name="coupon_code" placeholder="Enter code" class="ct-input" style="flex:1; text-transform: uppercase;">
                                <button type="submit" class="ct-updateBtn" style="width: auto; margin-top: 0; padding: 10px 16px;">Apply</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
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
    const allPostcodes = @json($postcodes);
    const allLocations = @json($allLocations);
    const savedPostcode = @json(is_array(session('shipping_location')) ? (session('shipping_location')['postcode'] ?? '') : '');
    const savedCity = @json(is_array(session('shipping_location')) ? (session('shipping_location')['city'] ?? '') : '');

    function filterCities() {
        const stateSelect = document.getElementById('state-selector');
        const stateId = stateSelect.options[stateSelect.selectedIndex].dataset.id;
        const citySelector = document.getElementById('city-selector');
        
        citySelector.innerHTML = '<option value="">Select City</option>';

        if (!stateId) return;

        const cities = allLocations.filter(loc => loc.type === 'city' && loc.parent_id == stateId);
        cities.forEach(city => {
            const opt = document.createElement('option');
            opt.value = city.name;
            opt.textContent = city.name;
            opt.dataset.id = city.id;
            if (city.name === savedCity) opt.selected = true;
            citySelector.appendChild(opt);
        });

        filterPostcodes();
    }

    function filterPostcodes() {
        const state = document.getElementById('state-selector').value;
        const citySelect = document.getElementById('city-selector');
        const cityId = citySelect.options[citySelect.selectedIndex].dataset.id;
        const selector = document.getElementById('postcode-selector');
        
        // If we have specific postcodes for this city in our database, we could filter them.
        // For now, we still use the general postcode list filtering by state prefix if no city-specific ones exist.
        
        const citySpecificPostcodes = allLocations.filter(loc => loc.type === 'postcode' && loc.parent_id == cityId);
        
        if (citySpecificPostcodes.length > 0) {
            selector.innerHTML = '<option value="">Select Postcode</option>';
            citySpecificPostcodes.forEach(pc => {
                const opt = document.createElement('option');
                opt.value = pc.code;
                opt.textContent = pc.code;
                if (pc.code === savedPostcode) opt.selected = true;
                selector.appendChild(opt);
            });
            return;
        }

        // Fallback to prefix-based filtering if no specific postcodes for city
        const prefixes = {
            'NSW': ['1', '2'], 'VIC': ['3'], 'QLD': ['4', '9'], 'SA': ['5'], 'WA': ['6'], 'TAS': ['7'], 'NT': ['0'], 'ACT': ['0', '2']
        };
        const allowedPrefixes = prefixes[state] || [];

        // Keep current selection if valid
        const currentVal = selector.value;
        selector.innerHTML = '<option value="">Select Postcode</option>';
        allPostcodes.forEach(pc => {
            const matches = allowedPrefixes.some(pref => pc.toString().startsWith(pref));
            if (matches) {
                const opt = document.createElement('option');
                opt.value = pc;
                opt.textContent = pc;
                if (pc.toString() === savedPostcode.toString()) opt.selected = true;
                selector.appendChild(opt);
            }
        });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', () => {
        filterCities();
    });

    async function updateQtyAJAX(id, change) {
        console.log('Update Qty Request:', id, change);
        const itemCard = document.getElementById('item-card-' + id);
        const qtySpan = document.getElementById('item-qty-' + id);
        const summaryPanel = document.getElementById('summary-panel');
        
        let newQty = parseInt(qtySpan.textContent) + change;
        if (newQty < 1) return;

        summaryPanel.classList.add('loading');

        try {
            const response = await fetch(`/cart/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ qty: newQty, _method: 'PATCH' })
            });

            const data = await response.json();
            if (data.success) {
                updateCartDOM(data);
                qtySpan.textContent = newQty;
                document.getElementById('item-total-' + id).textContent = '$' + data.item_subtotal;
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            showToast('Could not update cart', 'error');
        } finally {
            summaryPanel.classList.remove('loading');
        }
    }

    async function removeCartItemAJAX(id) {
        if (!confirm('Remove this item from your cart?')) return;

        const itemCard = document.getElementById('item-card-' + id);
        const summaryPanel = document.getElementById('summary-panel');
        
        summaryPanel.classList.add('loading');

        try {
            const response = await fetch(`/cart/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            });

            const data = await response.json();
            if (data.success) {
                if (data.cart_count === 0) {
                    location.reload(); // Reload to show empty state
                    return;
                }
                itemCard.remove();
                updateCartDOM(data);
            }
        } catch (error) {
            console.error('Error removing item:', error);
            showToast('Could not remove item', 'error');
        } finally {
            summaryPanel.classList.remove('loading');
        }
    }

    async function updateShippingAJAX(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const summaryPanel = document.getElementById('summary-panel');
        const shippingPanel = document.getElementById('shipping-panel');

        summaryPanel.classList.add('loading');
        shippingPanel.classList.add('loading');

        try {
            const response = await fetch('{{ route('cart.shipping.set') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const data = await response.json();
            if (data.success) {
                updateCartDOM(data);
                // Update location text
                document.getElementById('current-location-text').style.display = 'block';
                document.getElementById('loc-state').textContent = formData.get('state');
                document.getElementById('loc-country').textContent = formData.get('country');
                // showToast(data.message, 'success');
            }
        } catch (error) {
            console.error('Error updating shipping:', error);
            showToast('Could not update shipping', 'error');
        } finally {
            summaryPanel.classList.remove('loading');
            shippingPanel.classList.remove('loading');
        }
    }

    function updateCartDOM(data) {
        // Sidebar Summary
        document.getElementById('summary-subtotal').textContent = '$' + data.formatted_subtotal;
        document.getElementById('summary-shipping-name').textContent = data.shipping.name;
        
        const costRow = document.getElementById('shipping-cost-row');
        if (data.shipping.cost > 0) {
            costRow.style.display = 'flex';
            document.getElementById('summary-shipping-cost').textContent = '$' + data.formatted_shipping_cost;
        } else {
            costRow.style.display = 'none';
        }

        document.getElementById('summary-total').textContent = '$' + data.formatted_total;

        // Min Order Warning
        const minOrderSection = document.getElementById('min-order-section');
        const checkoutSection = document.getElementById('checkout-button-section');
        
        if (data.minOrderMet) {
            minOrderSection.style.display = 'none';
            checkoutSection.style.display = 'block';
        } else {
            minOrderSection.style.display = 'block';
            checkoutSection.style.display = 'none';
            document.getElementById('min-order-current-total').textContent = data.formatted_subtotal;
            document.getElementById('min-order-gap').textContent = data.min_order_gap;
        }

        // Header Badge (from layout)
        if (typeof updateCartUI === 'function') {
            updateCartUI(data);
        }
    }
</script>
@endsection

