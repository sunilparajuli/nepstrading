@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<style>
    :root {
        --checkout-bg: hsl(var(--bg));
        --checkout-muted: hsl(var(--muted));
        --checkout-border: hsl(var(--border));
        --checkout-primary: hsl(var(--primary));
        --checkout-primary-fg: hsl(var(--primary-fg));
        --checkout-sale: hsl(var(--sale));
    }

    .ch-header { padding: 48px 0; background: var(--checkout-muted); text-align: center; }
    .ch-headerTitle { font-family: 'DM Serif Display', serif; font-size: 42px; color: hsl(var(--fg)); margin: 0 0 8px; }
    .ch-headerDesc { font-size: 16px; color: hsl(var(--muted-fg)); margin: 0; }

    .ch-wrap { max-width: 1200px; margin: 0 auto; padding: 40px 16px; display: flex; gap: 40px; align-items: flex-start; }
    @media (max-width: 1024px) { .ch-wrap { flex-direction: column; } .ch-side { width: 100% !important; } }

    .ch-main { flex: 1; min-width: 0; }
    .ch-section { background: var(--checkout-bg); border-radius: 16px; border: 1px solid var(--checkout-border); padding: 32px; margin-bottom: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .ch-sectionTitle { font-family: 'DM Serif Display', serif; font-size: 24px; color: hsl(var(--fg)); margin: 0 0 24px; padding-bottom: 16px; border-bottom: 1px solid var(--checkout-border); }

    .ch-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    @media (max-width: 640px) { .ch-grid { grid-template-columns: 1fr; } }

    .ch-group { margin-bottom: 20px; position: relative; }
    .ch-label { display: block; font-size: 13px; font-weight: 600; color: hsl(var(--muted-fg)); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
    
    .ch-input, .ch-textarea {
        width: 100%; padding: 12px 16px; border: 1.5px solid var(--checkout-border); border-radius: 10px;
        background: var(--checkout-bg); font-size: 15px; color: hsl(var(--fg)); outline: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .ch-input:focus, .ch-textarea:focus { border-color: var(--checkout-primary); box-shadow: 0 0 0 4px hsla(var(--primary-hue), var(--primary-sat), var(--primary-light), 0.1); }
    .ch-input::placeholder { color: hsl(var(--muted-fg) / 0.5); }

    .ch-side { width: 400px; flex-shrink: 0; position: sticky; top: 32px; }
    .ch-panel { background: var(--checkout-bg); border-radius: 16px; border: 1px solid var(--checkout-border); padding: 32px; position: relative; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .ch-panelTitle { font-family: 'DM Serif Display', serif; font-size: 22px; color: hsl(var(--fg)); margin: 0 0 24px; }

    .ch-orderItem { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 14px; color: hsl(var(--fg)); }
    .ch-orderName { color: hsl(var(--muted-fg)); max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ch-orderQty { font-weight: 700; color: hsl(var(--fg)); margin-left: 4px; }
    .ch-orderPrice { font-weight: 600; }

    .ch-summaryTable { margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--checkout-border); }
    .ch-sumRow { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; }
    .ch-sumLabel { color: hsl(var(--muted-fg)); }
    .ch-sumVal { font-weight: 600; color: hsl(var(--fg)); }
    .ch-totalRow { margin-top: 16px; padding-top: 16px; border-top: 2px solid var(--checkout-border); }
    .ch-totalLabel { font-size: 18px; font-weight: 800; color: hsl(var(--fg)); }
    .ch-totalPrice { font-size: 28px; font-weight: 900; color: var(--checkout-sale); }

    .ch-paymentCard { background: var(--checkout-muted); border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid var(--checkout-border); }
    .ch-paymentTitle { font-size: 14px; font-weight: 700; text-transform: uppercase; color: hsl(var(--fg)); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .ch-paymentDesc { font-size: 13px; color: hsl(var(--muted-fg)); line-height: 1.5; margin-bottom: 16px; }
    .ch-bankDetails { display: grid; gap: 8px; }
    .ch-bankRow { display: flex; justify-content: space-between; font-size: 13px; }
    .ch-bankLabel { color: hsl(var(--muted-fg)); }
    .ch-bankVal { font-weight: 700; color: hsl(var(--fg)); font-family: 'JetBrains Mono', monospace; }

    .ch-submitBtn {
        width: 100%; padding: 20px; background: var(--checkout-primary); color: var(--checkout-primary-fg);
        border: none; border-radius: 12px; font-size: 16px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.1em; cursor: pointer; transition: all 0.3s; margin-top: 16px;
        box-shadow: 0 4px 12px hsla(145, 63%, 32%, 0.2);
    }
    .ch-submitBtn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px hsla(145, 63%, 32%, 0.3); background: hsl(145 63% 28%); }
    .ch-submitBtn:active { transform: translateY(0); }

    /* Autocomplete Dropdown */
    .ch-autocomplete {
        position: absolute; top: calc(100% + 4px); left: 0; right: 0;
        background: var(--checkout-bg); border: 1px solid var(--checkout-border);
        border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        z-index: 1000; overflow: hidden; display: none;
    }
    .ch-autocomplete.active { display: block; }
    .ch-suggestion {
        padding: 12px 16px; cursor: pointer; font-size: 14px; color: hsl(var(--fg));
        border-bottom: 1px solid var(--checkout-border); transition: background 0.2s;
    }
    .ch-suggestion:last-child { border-bottom: none; }
    .ch-suggestion:hover { background: var(--checkout-muted); }
    .ch-suggestion-main { font-weight: 600; display: block; }
    .ch-suggestion-sub { font-size: 12px; color: hsl(var(--muted-fg)); }

    .ch-error { color: var(--checkout-sale); font-size: 12px; margin-top: 4px; font-weight: 500; }
</style>

<div class="ch-header">
    <h1 class="ch-headerTitle">Checkout</h1>
    <p class="ch-headerDesc">Securely complete your purchase.</p>
</div>

<div class="ch-wrap">
    @if(session('error'))
        <div style="background: hsl(0 84% 97%); border: 1px solid hsl(0 84% 90%); color: hsl(0 84% 40%); padding: 16px; border-radius: 12px; margin-bottom: 24px; width: 100%;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="ch-main">
        @csrf
        
        <!-- Billing Details -->
        <div class="ch-section">
            <h2 class="ch-sectionTitle">Billing Details</h2>
            
            <div class="ch-grid">
                <div class="ch-group">
                    <label class="ch-label">First Name *</label>
                    <input type="text" name="billing_first_name" required value="{{ old('billing_first_name') }}" class="ch-input">
                    @error('billing_first_name') <p class="ch-error">{{ $message }}</p> @enderror
                </div>
                <div class="ch-group">
                    <label class="ch-label">Last Name *</label>
                    <input type="text" name="billing_last_name" required value="{{ old('billing_last_name') }}" class="ch-input">
                    @error('billing_last_name') <p class="ch-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="ch-grid">
                <div class="ch-group">
                    <label class="ch-label">Email Address *</label>
                    <input type="email" name="billing_email" required value="{{ old('billing_email') }}" class="ch-input">
                    @error('billing_email') <p class="ch-error">{{ $message }}</p> @enderror
                </div>
                <div class="ch-group">
                    <label class="ch-label">Phone *</label>
                    <input type="text" name="billing_phone" required value="{{ old('billing_phone') }}" class="ch-input">
                    @error('billing_phone') <p class="ch-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="ch-group">
                <label class="ch-label">Street Address *</label>
                <input type="text" name="billing_address" id="street-address" required value="{{ old('billing_address') }}"
                    placeholder="Search your street address..." autocomplete="off" class="ch-input">
                <div id="address-loading" style="position: absolute; right: 12px; top: 38px; display: none;">
                    <svg style="width: 20px; height: 20px; animation: spin 1s linear infinite;" viewBox="0 0 24 24"><path fill="currentColor" d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z"/></svg>
                </div>
                <div id="address-suggestions" class="ch-autocomplete"></div>
                @error('billing_address') <p class="ch-error">{{ $message }}</p> @enderror
            </div>

            <div class="ch-grid">
                <div class="ch-group">
                    <label class="ch-label">Town / City *</label>
                    <input type="text" name="billing_city" id="billing_city" required value="{{ old('billing_city', $location['city'] ?? '') }}" class="ch-input">
                    @error('billing_city') <p class="ch-error">{{ $message }}</p> @enderror
                </div>
                <div class="ch-group">
                    <label class="ch-label">Postcode / ZIP *</label>
                    <input type="text" name="billing_postcode" id="billing_postcode" required value="{{ old('billing_postcode', $location['postcode'] ?? '') }}" class="ch-input">
                    @error('billing_postcode') <p class="ch-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="ch-group" style="margin-bottom: 0;">
                <label class="ch-label">State</label>
                <select name="billing_state" id="billing_state" class="ch-input">
                    <option value="">Select State</option>
                    @foreach($states as $state)
                        <option value="{{ $state->code }}" {{ (old('billing_state') ?? ($location['state'] ?? '')) == $state->code ? 'selected' : '' }}>
                            {{ $state->name }} ({{ $state->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="ch-section">
            <h2 class="ch-sectionTitle">Additional Information</h2>
            <div class="ch-group" style="margin-bottom: 0;">
                <label class="ch-label">Order Notes (optional)</label>
                <textarea name="order_notes" rows="4" 
                    placeholder="Notes about your order, e.g. special notes for delivery."
                    class="ch-textarea">{{ old('order_notes') }}</textarea>
            </div>
        </div>
    </form>

    <!-- Sidebar Summary -->
    <aside class="ch-side">
        <div class="ch-panel">
            <h2 class="ch-panelTitle">Your Order</h2>
            
            <div style="margin-bottom: 24px;">
                @foreach($cart as $id => $item)
                <div class="ch-orderItem">
                    <span>
                        <span class="ch-orderName">{{ $item['name'] }}</span>
                        <span class="ch-orderQty">× {{ $item['qty'] }}</span>
                    </span>
                    <span class="ch-orderPrice">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="ch-summaryTable">
                <div class="ch-sumRow">
                    <span class="ch-sumLabel">Subtotal</span>
                    <span class="ch-sumVal">${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="ch-sumRow">
                    <span class="ch-sumLabel">Shipping ({{ $shipping['name'] }})</span>
                    <span class="ch-sumVal">${{ number_format($shipping['cost'] ?? 0, 2) }}</span>
                </div>
                @if($discount > 0)
                <div class="ch-sumRow" style="color: var(--checkout-sale);">
                    <span class="ch-sumLabel">Coupon Discount</span>
                    <span class="ch-sumVal">-${{ number_format($discount, 2) }}</span>
                </div>
                @endif
                <div class="ch-sumRow ch-totalRow">
                    <span class="ch-totalLabel">Total</span>
                    <span class="ch-totalPrice">${{ number_format($total, 2) }}</span>
                </div>
            </div>

            <div class="ch-paymentCard">
                <h3 class="ch-paymentTitle">
                    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Direct Bank Transfer
                </h3>
                <p class="ch-paymentDesc">Use Order ID as payment reference. Order shipped after funds clear.</p>
                <div class="ch-bankDetails">
                    <div class="ch-bankRow"><span class="ch-bankLabel">Bank:</span> <span class="ch-bankVal">Nepstrading</span></div>
                    <div class="ch-bankRow"><span class="ch-bankLabel">B.S.B:</span> <span class="ch-bankVal">063-581</span></div>
                    <div class="ch-bankRow"><span class="ch-bankLabel">A/C:</span> <span class="ch-bankVal">10599057</span></div>
                    <div class="ch-bankRow"><span class="ch-bankLabel">PayID:</span> <span class="ch-bankVal">0401596751</span></div>
                </div>
            </div>

            <button type="submit" form="checkout-form" class="ch-submitBtn">
                Place Order
            </button>
            <p style="text-align: center; font-size: 11px; color: hsl(var(--muted-fg)); margin-top: 16px;">Secure 256-bit SSL Encrypted Checkout</p>
        </div>
    </aside>
</div>

<script>
    const addressInput = document.getElementById('street-address');
    const suggestionsBox = document.getElementById('address-suggestions');
    const loadingIcon = document.getElementById('address-loading');
    
    let debounceTimer;

    addressInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = addressInput.value.trim();
        
        if (query.length < 3) {
            suggestionsBox.classList.remove('active');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchAddressSuggestions(query);
        }, 500);
    });

    // Close suggestions on click outside
    document.addEventListener('click', (e) => {
        if (!addressInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.remove('active');
        }
    });

    async function fetchAddressSuggestions(query) {
        loadingIcon.style.display = 'block';
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&countrycodes=au&addressdetails=1&q=${encodeURIComponent(query)}`;
            const response = await fetch(url, {
                headers: { 'Accept-Language': 'en-AU' }
            });
            const data = await response.json();
            
            displaySuggestions(data);
        } catch (error) {
            console.error('Error fetching addresses:', error);
        } finally {
            loadingIcon.style.display = 'none';
        }
    }

    function displaySuggestions(results) {
        suggestionsBox.innerHTML = '';
        if (results.length === 0) {
            suggestionsBox.classList.remove('active');
            return;
        }

        results.forEach(res => {
            const div = document.createElement('div');
            div.className = 'ch-suggestion';
            
            const addr = res.address;
            const mainText = res.display_name.split(',')[0];
            const subText = res.display_name.split(',').slice(1).join(',').trim();

            div.innerHTML = `
                <span class="ch-suggestion-main">${mainText}</span>
                <span class="ch-suggestion-sub">${subText}</span>
            `;

            div.addEventListener('click', () => {
                applyAddress(res);
            });
            suggestionsBox.appendChild(div);
        });

        suggestionsBox.classList.add('active');
    }

    function applyAddress(res) {
        const addr = res.address;
        
        // Populate fields
        // Nominatim uses different keys for suburb/city
        const street = res.display_name.split(',')[0] + (addr.road ? ', ' + addr.road : '');
        addressInput.value = res.display_name.split(',').slice(0, 2).join(', ').trim();
        
        document.getElementById('billing_city').value = addr.city || addr.town || addr.suburb || addr.village || '';
        document.getElementById('billing_postcode').value = addr.postcode || '';
        
        // Update State Dropdown
        if (addr.state) {
            const stateSelect = document.getElementById('billing_state');
            for (let i = 0; i < stateSelect.options.length; i++) {
                const opt = stateSelect.options[i];
                // Match by name (text contains state name) or value (code)
                if (opt.text.toLowerCase().includes(addr.state.toLowerCase()) || 
                    opt.value.toLowerCase() === addr.state.toLowerCase()) {
                    stateSelect.selectedIndex = i;
                    break;
                }
            }
        }

        // Hide suggestions
        suggestionsBox.classList.remove('active');
    }
</script>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>
@endsection
