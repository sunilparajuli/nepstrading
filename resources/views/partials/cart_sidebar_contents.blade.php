@php
    $cart = session()->get('cart', []);
    $subtotal = 0;
    $totalItems = 0;
    foreach($cart as $item) {
        $subtotal += $item['price'] * ($item['qty'] ?? 1);
        $totalItems += ($item['qty'] ?? 1);
    }
    $threshold = 65;
    $remaining = max($threshold - $subtotal, 0);
    $progress = min(($subtotal / $threshold) * 100, 100);
@endphp

<div class="s-cartRow">
    <span>Subtotal</span><span style="font-weight: 600;">${{ number_format($subtotal, 2) }}</span>
</div>

<a href="{{ route('checkout.index') }}" class="s-checkoutBtn">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
    Checkout
</a>

<div style="margin-bottom: 16px;">
    <p style="font-size: 12px; color: hsl(var(--muted-fg)); margin-bottom: 8px; margin-top: 0;">
        @if($remaining > 0)
            Spend ${{ number_format($remaining, 2) }} more for free shipping!
        @else
            🎉 You qualify for free shipping!
        @endif
    </p>
    <div class="s-progressTrack"><div class="s-progressFill" style="width: {{ $progress }}%;"></div></div>
</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
    <h4 style="font-size: 14px; font-weight: 600; color: hsl(var(--fg)); margin: 0;">Products</h4>
    <span style="font-size: 12px; color: hsl(var(--muted-fg));" id="cart-item-count-sidebar">({{ $totalItems }})</span>
</div>

@if(count($cart) > 0)
    <div id="cart-items-list">
        @foreach($cart as $id => $item)
            <div class="s-cartItem">
                <img src="{{ $item['image'] ?? 'https://placehold.co/100' }}" alt="{{ $item['name'] }}" class="s-cartItemImg" />
                <div class="s-cartItemInfo">
                    <p class="s-cartItemName">{{ $item['name'] }}</p>
                    <div style="display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                        <span style="font-size: 14px; font-weight: 700; color: hsl(var(--fg));">${{ number_format($item['price'], 2) }}</span>
                    </div>
                    <div class="s-qtyRow">
                        <form id="cart-update-{{ $id }}" action="{{ route('cart.update', $id) }}" method="POST" style="margin:0; display:flex; align-items:center; gap:6px;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="qty" value="{{ $item['qty'] ?? 1 }}" id="cart-qty-{{ $id }}">
                            <button type="button" class="s-qtyBtnRound" onclick="updateCartQty({{ $id }}, -1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                            <span class="s-qtyDisplay" id="cart-qty-display-{{ $id }}">{{ $item['qty'] ?? 1 }}</span>
                            <button type="button" class="s-qtyBtnRound" onclick="updateCartQty({{ $id }}, 1)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </form>
                        
                        <form action="{{ route('cart.remove', $id) }}" method="POST" style="margin-left: auto;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="s-removeBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="hsl(0, 84%, 55%)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p style="font-size: 14px; color: hsl(var(--muted-fg)); text-align: center; padding: 32px 0;">Your cart is empty</p>
@endif
