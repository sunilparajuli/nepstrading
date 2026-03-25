<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Nepstrading</title>
    <!-- Tailwind CSS (for base utilities if needed, though we will port the exact CSS) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    @php
        $sitePrimaryColor = \App\Models\SiteSetting::getValue('primary_color', '#5eba7d');
        
        // Simple Hex to HSL Conversion
        function hexToHslComponents($hex) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) { $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2]; }
            $r = hexdec(substr($hex, 0, 2)) / 255;
            $g = hexdec(substr($hex, 2, 2)) / 255;
            $b = hexdec(substr($hex, 4, 2)) / 255;
            
            $max = max($r, $g, $b);
            $min = min($r, $g, $b);
            $l = ($max + $min) / 2;
            $h = $s = 0;

            if ($max != $min) {
                $d = $max - $min;
                $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                switch ($max) {
                    case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                    case $g: $h = ($b - $r) / $d + 2; break;
                    case $b: $h = ($r - $g) / $d + 4; break;
                }
                $h /= 6;
            }
            return [round($h * 360), round($s * 100) . '%', round($l * 100) . '%'];
        }

        $hsl = hexToHslComponents($sitePrimaryColor);
        $primaryVar = implode(', ', $hsl);
        
        // For darker variant (promo bar etc) - just reduce L by 5-10%
        $lVal = (int)str_replace('%', '', $hsl[2]);
        $promoVar = $hsl[0] . ', ' . $hsl[1] . ', ' . max(0, $lVal - 7) . '%';
    @endphp
    <style>
        :root {
            --primary: {{ $primaryVar }};
            --primary-fg: 0, 0%, 100%;
            --fg: 220, 15%, 10%;
            --bg: 0, 0%, 100%;
            --muted: 210, 20%, 98%;
            --muted-fg: 215, 15%, 45%;
            --border: 214, 20%, 92%;
            --accent: 28, 90%, 55%;
            --accent-fg: 0, 0%, 100%;
            --promo-bg: {{ $promoVar }};
            --promo-fg: 0, 0%, 100%;
            --sale: 0, 85%, 60%;
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.04), 0 4px 6px -2px rgba(0,0,0,0.02);
            --shadow-lg: 0 25px 50px -12px rgba(0,0,0,0.08);
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: hsl(var(--bg));
            color: hsl(var(--fg));
            margin: 0;
            padding: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* --- React Inline Styles Ported to CSS --- */
        .s-announcementBar {
            display: flex; align-items: center; justify-content: center; gap: 16px;
            flex-wrap: wrap; background: hsl(var(--primary)); color: hsl(var(--primary-fg));
            font-size: 14px; padding: 8px 16px; text-align: center;
        }
        .s-navBar {
            background: hsla(var(--bg), 0.8); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid hsl(var(--border));
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .s-navInner {
            max-width: 1200px; margin: 0 auto; padding: 0 16px; position: relative;
        }
        .s-navTopRow {
            display: flex; align-items: center; gap: 16px; padding: 12px 0;
        }
        .s-logo {
            font-family: 'DM Serif Display', serif; font-size: 26px; color: hsl(var(--fg));
            flex-shrink: 0; margin-right: 24px; text-decoration: none; font-weight: 500;
            letter-spacing: -0.02em;
        }
        .s-searchWrap {
            flex: 1; display: flex; max-width: 512px;
        }
        .s-searchSelect {
            border: 1px solid hsl(var(--border)); border-right: none;
            border-radius: 6px 0 0 6px; padding: 10px 12px; font-size: 14px;
            background: hsl(var(--muted)); color: hsl(var(--fg)); outline: none;
        }
        .s-searchInputWrap {
            position: relative; flex: 1;
        }
        .s-searchInput {
            width: 100%; border: 1px solid hsl(var(--border));
            border-radius: 0 6px 6px 0; padding: 10px 40px 10px 16px; font-size: 14px;
            background: hsl(var(--bg)); color: hsl(var(--fg)); outline: none;
        }
        .s-searchIcon {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            color: hsl(var(--muted-fg));
        }
        .s-iconBtn {
            padding: 8px; border-radius: 50%; background: transparent; border: none;
            cursor: pointer; position: relative; display: flex; align-items: center; justify-content: center;
        }
        .s-cartBadge {
            position: absolute; top: -4px; right: -4px; width: 20px; height: 20px;
            border-radius: 50%; background: hsl(var(--primary)); color: hsl(var(--primary-fg));
            font-size: 10px; font-weight: 700; display: flex; align-items: center; justify-content: center;
        }
        .s-navLinks {
            display: flex; align-items: center; gap: 0; overflow-x: auto;
            padding-bottom: 4px; margin: 0 -12px;
        }
        .s-navLinks::-webkit-scrollbar { display: none; }
        .s-navLink {
            font-size: 14px; font-weight: 500; padding: 8px 12px; border: none;
            background: transparent; cursor: pointer; white-space: nowrap;
            color: hsl(var(--fg)); transition: color 0.2s; text-decoration: none; display: flex; align-items: center; gap: 4px;
        }
        .s-navLink:hover { color: hsl(var(--primary)); }
        .s-navLink.highlight { color: hsl(var(--accent)); }
        
        .s-badgeSeason {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600;
            background: hsl(var(--accent)); color: hsl(var(--accent-fg));
        }
        .s-promoStrip {
            text-align: center; padding: 12px 0; font-size: 14px;
            background: hsl(var(--promo-bg)); color: hsl(var(--promo-fg));
        }

        .s-footer {
            border-top: 1px solid hsl(var(--border)); padding: 32px 0;
            background: hsl(var(--bg));
        }
        .s-footerInner {
            max-width: 1200px; margin: 0 auto; padding: 0 16px;
            display: flex; flex-wrap: wrap; gap: 32px; justify-content: space-between;
            font-size: 14px; color: hsl(var(--muted-fg));
        }
        .s-footerLogo {
            font-family: 'DM Serif Display', serif; font-size: 18px; color: hsl(var(--fg)); margin-bottom: 12px;
        }
        .s-footerHeading { font-weight: 600; color: hsl(var(--fg)); margin-bottom: 8px; }
        .s-footerLink { color: hsl(var(--muted-fg)); text-decoration: none; display: block; margin-bottom: 4px; }
        .s-footerLink:hover { color: hsl(var(--primary)); }

        /* cart sidebar */
        .s-cartSidebar {
            border-left: 1px solid hsl(var(--border)); height: 100vh; overflow-y: auto;
            background: hsl(var(--bg)); padding: 16px; position: sticky; top: 0;
        }
        .s-cartHeader {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;
        }
        .s-cartTitle { font-weight: 600; color: hsl(var(--fg)); margin: 0; font-size: 18px; }
        .s-cartLink { font-size: 12px; color: hsl(var(--primary)); text-decoration: none; }
        .s-cartRow {
            display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 4px;
            color: hsl(var(--fg)); font-weight: 400;
        }
        .s-cartRow.bold { color: hsl(var(--primary)); font-weight: 600; }
        .s-checkoutBtn {
            width: 100%; padding: 12px 0; border-radius: 6px; font-weight: 600; font-size: 14px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            border: none; cursor: pointer; background: hsl(var(--fg)); color: hsl(var(--bg));
            transition: opacity 0.2s; margin-bottom: 16px; text-decoration: none;
        }
        .s-checkoutBtn:hover { opacity: 0.9; }
        
        .s-progressTrack { width: 100%; height: 6px; border-radius: 999px; background: hsl(var(--border)); }
        .s-progressFill { height: 6px; border-radius: 999px; background: hsl(var(--fg)); transition: width 0.3s; }
        
        .s-cartItem { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid hsl(var(--border)); }
        .s-cartItemImg { width: 56px; height: 56px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }
        .s-cartItemInfo { flex: 1; min-width: 0; }
        .s-cartItemName { font-size: 14px; font-weight: 500; color: hsl(var(--fg)); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin: 0; }
        .s-cartItemWeight { font-size: 12px; color: hsl(var(--muted-fg)); margin: 0; }
        .s-qtyRow { display: flex; align-items: center; gap: 6px; margin-top: 8px; }
        .s-qtyBtnRound {
            width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 16px; font-weight: 700; border: 2px solid hsl(var(--primary)); cursor: pointer;
            background: transparent; color: hsl(var(--primary)); transition: all 0.2s ease;
            line-height: 1; padding: 0;
        }
        .s-qtyBtnRound:hover {
            background: hsl(var(--primary)); color: hsl(var(--primary-fg));
            transform: scale(1.1); box-shadow: 0 2px 8px hsla(var(--primary), 0.35);
        }
        .s-qtyBtnRound:active { transform: scale(0.95); }
        .s-qtyDisplay {
            min-width: 28px; text-align: center; font-size: 15px; font-weight: 700;
            color: hsl(var(--fg)); user-select: none;
        }
        .s-qtyNum { width: 24px; text-align: center; font-size: 14px; font-weight: 600; color: hsl(var(--fg)); border: none; background: transparent; }
        .s-removeBtn { margin-left: auto; padding: 4px; background: transparent; border: none; cursor: pointer; border-radius: 50%; transition: background 0.2s; }
        .s-removeBtn:hover { background: hsl(0, 84%, 95%); }
        .s-priceSale { color: hsl(var(--sale)); }

        /* overlay styles */
        .s-overlay { position: fixed; inset: 0; z-index: 50; display: none; }
        .s-overlay.open { display: block; }
        .s-overlayBg { position: absolute; inset: 0; background: hsla(210, 11%, 15%, 0.4); cursor: pointer; }
        .s-overlayPanel {
            position: absolute; right: 0; top: 0; bottom: 0; width: 320px; max-width: 100%;
            background: hsl(var(--bg)); border-left: 1px solid hsl(var(--border));
            transform: translateX(100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .s-overlay.open .s-overlayPanel { transform: translateX(0); }
        .s-overlayHeader { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid hsl(var(--border)); background: hsl(var(--bg)); }
        .s-closeBtn { padding: 4px; background: transparent; border: none; cursor: pointer; border-radius: 4px; display: flex; align-items: center; justify-content: center; }
        .s-closeBtn:hover { background: hsl(var(--muted)); }

        /* Nav dropdown */
        .s-navDropdown { position: static; }
        .s-navDropItem:hover { background: hsl(var(--muted)); color: hsl(var(--primary)); }
        
        /* Megamenu specific styles */
        .s-megaMenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 16px;
            right: 16px;
            width: calc(100% - 32px);
            z-index: 100;
            background: hsl(var(--bg));
            border-top: 2px solid hsl(var(--primary));
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 40px 0;
            animation: slideUpFade 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translate(-50%, 10px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
        .s-navDropdown:hover .s-megaMenu { display: block; }
        .s-megaInner { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 24px; 
            display: grid; 
            grid-template-columns: 300px 1fr;
            gap: 48px; 
        }
        .s-megaTitle { 
            font-size: 15px; 
            font-weight: 800; 
            color: hsl(var(--fg)); 
            margin-bottom: 20px; 
            text-transform: uppercase; 
            letter-spacing: 0.1em;
            position: relative;
            padding-bottom: 8px;
        }
        .s-megaTitle::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: hsl(var(--primary));
        }
        .s-megaLink { 
            display: flex; 
            align-items: center;
            font-size: 14px; 
            color: hsl(var(--muted-fg)); 
            text-decoration: none; 
            padding: 6px 0;
            transition: all 0.2s; 
        }
        .s-megaLink:hover { color: hsl(var(--primary)); }
        .s-megaImage { 
            width: 100%; 
            height: 180px; 
            object-fit: cover; 
            border-radius: 12px;
        }

        /* Search megamenu */
        .s-searchMega {
            position: absolute; top: 100%; left: 0; right: 0; z-index: 50;
            background: hsl(var(--bg)); border: 1px solid hsl(var(--border)); border-radius: 0 0 12px 12px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.12); max-height: 420px; overflow-y: auto;
        }
        .s-searchItem {
            display: flex; align-items: center; gap: 12px; padding: 10px 16px;
            text-decoration: none; color: hsl(var(--fg)); transition: background 0.15s; cursor: pointer;
            border-bottom: 1px solid hsl(var(--border));
        }
        .s-searchItem:last-child { border-bottom: none; }
        .s-searchItem:hover { background: hsl(var(--muted)); }
        .s-searchItemImg { width: 48px; height: 48px; border-radius: 6px; object-fit: cover; flex-shrink: 0; background: hsl(var(--muted)); }
        .s-searchItemName { font-size: 14px; font-weight: 600; margin: 0; }
        .s-searchItemCat { font-size: 11px; color: hsl(var(--muted-fg)); margin: 2px 0 0; }
        .s-searchItemPrice { margin-left: auto; font-size: 14px; font-weight: 700; color: hsl(var(--primary)); flex-shrink: 0; }
        .s-searchEmpty { padding: 24px; text-align: center; font-size: 14px; color: hsl(var(--muted-fg)); }

        /* Icons placeholder styles for raw HTML if SVG is inline */
        svg { width: 1em; height: 1em; }

        /* Toast Styles */
        #toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .s-toast {
            background: hsl(var(--fg));
            color: hsl(var(--bg));
            padding: 12px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            min-width: 280px;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .s-toast.fade-out {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        .s-toast-success { border-left: 4px solid hsl(var(--primary)); }
    </style>
</head>
<body>
    <!-- Announcement -->
    <div class="s-announcementBar">
        <span style="display: flex; items-align: center; gap: 4px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
            <strong>In Season</strong> Browse the best in seasonal flavour
        </span>
    </div>

    <div style="display: flex;">
        <!-- Main content -->
        <div style="flex: 1; min-width: 0; display: flex; flex-direction: column; min-height: 100vh;">
            
            <!-- Nav -->
            <div class="s-navBar">
                <div class="s-navInner">
                    <div class="s-navTopRow">
                        <a href="{{ url('/') }}" class="s-logo">
                            @php $siteLogo = \App\Models\SiteSetting::getValue('logo'); @endphp
                            @if($siteLogo)
                                <img src="{{ $siteLogo }}" alt="Nepstrading" style="height: 40px; width: auto; object-fit: contain;">
                            @else
                                Nepstrading
                            @endif
                        </a>
                        <div class="s-searchWrap hidden md:flex" style="position: relative;">
                            <select id="searchCategory" class="s-searchSelect">
                                <option value="">All</option>
                                @foreach(\App\Models\Category::whereNull('parent_id')->orderBy('name')->get() as $sc)
                                    <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                                @endforeach
                            </select>
                            <div class="s-searchInputWrap">
                                <input id="searchInput" class="s-searchInput" placeholder="Search for products..." autocomplete="off" />
                                <span class="s-searchIcon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </span>
                            </div>
                            <!-- Search Results Megamenu -->
                            <div id="searchResults" class="s-searchMega" style="display: none;"></div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-left: auto;">
                            @auth
                                <a href="{{ route('wishlist.index') }}" class="s-iconBtn" title="Wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--fg))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                                    @if($wishlistCount > 0)
                                        <span class="s-cartBadge">{{ $wishlistCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="s-iconBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--fg))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="s-iconBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--fg))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </a>
                            @endauth
                            <button type="button" class="s-iconBtn" onclick="toggleCart()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--fg))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                                <span id="cart-badge-count" class="s-cartBadge" style="{{ (session()->has('cart') && count(session('cart')) > 0) ? '' : 'display:none;' }}">
                                    {{ session()->has('cart') ? count(session('cart')) : 0 }}
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="s-navLinks">
                        <a href="{{ url('/') }}" class="s-navLink">Home</a>
                        @php $navCategories = \App\Models\Category::with('children')->whereNull('parent_id')->orderBy('name')->get(); @endphp
                        @foreach($navCategories as $navCat)
                            <div class="s-navDropdown">
                                <a href="{{ route('categories.show', $navCat) }}" class="s-navLink">
                                    {{ $navCat->name }}
                                    @if($navCat->children->count() > 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    @endif
                                </a>
                                @if($navCat->children->count() > 0)
                                    <div class="s-megaMenu">
                                        <div class="s-megaInner">
                                            <div class="col-span-1">
                                                <h4 class="s-megaTitle">Explore {{ $navCat->name }}</h4>
                                                @if($navCat->image)
                                                    <div class="mb-4 overflow-hidden rounded-xl shadow-lg group">
                                                        <img src="{{ asset($navCat->image) }}" alt="{{ $navCat->name }}" class="s-megaImage transition-transform duration-500 group-hover:scale-110">
                                                    </div>
                                                @endif
                                                <p class="text-sm text-gray-500 font-medium leading-relaxed">Discover our premium selection of fresh and quality {{ strtolower($navCat->name) }}.</p>
                                                <a href="{{ route('categories.show', $navCat) }}" class="mt-4 inline-flex items-center text-sm font-bold text-[hsl(var(--primary))] hover:underline">
                                                    View All {{ $navCat->name }}
                                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </div>
                                            <div class="col-span-3 grid grid-cols-3 gap-12">
                                                @foreach($navCat->children->chunk(ceil($navCat->children->count() / 3)) as $chunk)
                                                    <div class="space-y-4">
                                                        @foreach($chunk as $sub)
                                                            <a href="{{ route('categories.show', $sub) }}" class="s-megaLink flex items-center group">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 mr-3 group-hover:bg-[hsl(var(--primary))] transition-colors"></span>
                                                                <span class="font-medium group-hover:translate-x-1 transition-transform">{{ $sub->name }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <a href="{{ route('products.index') }}" class="s-navLink highlight">All Products</a>
                        
                        <span style="margin-left: auto; flex-shrink: 0;">
                            <span class="s-badgeSeason">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                In season now!
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Promo -->
            <div class="s-promoStrip">
                Great Deals on your weekly shop <a href="{{ route('products.index') }}" style="color: hsl(var(--promo-fg)); font-weight: 600; text-decoration: underline;">Shop Now</a>
            </div>

            <!-- Page Content -->
            <main style="flex: 1;">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="s-footer">
                <div class="s-footerInner">
                    <div>
                        <h3 class="s-footerLogo">Nepstrading</h3>
                        <p style="max-width: 280px; margin: 0;">Your neighborhood grocery store, delivering fresh produce and quality goods right to your door.</p>
                    </div>
                    @php
                        $footerPages = \App\Models\Page::where('status', 'published')
                            ->where('show_on_footer', true)
                            ->whereNotNull('footer_section')
                            ->orderBy('sort_order', 'asc')
                            ->get()
                            ->groupBy('footer_section');
                    @endphp

                    @foreach(['Quick Links', 'Customer Service'] as $section)
                        @if(isset($footerPages[$section]))
                            <div>
                                <h4 class="s-footerHeading">{{ $section }}</h4>
                                @foreach($footerPages[$section] as $fPage)
                                    <a href="/page/{{ $fPage->slug }}" class="s-footerLink">{{ $fPage->title }}</a>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </footer>
        </div>

        <!-- Cart Overlay -->
        <div id="cartOverlay" class="s-overlay">
            <div class="s-overlayBg" onclick="toggleCart()"></div>
            
            <div class="s-overlayPanel">
                <div class="s-overlayHeader">
                    <span style="font-weight: 600; color: hsl(var(--fg));">Your cart</span>
                    <button class="s-closeBtn" onclick="toggleCart()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="hsl(var(--fg))" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                
                <div id="cart-sidebar-container" style="flex: 1; overflow-y: auto; padding: 16px;">
                    @include('partials.cart_sidebar_contents')
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCart() {
            const overlay = document.getElementById('cartOverlay');
            if (overlay.classList.contains('open')) {
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            } else {
                overlay.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        }

        function updateCartQty(id, delta) {
            const input = document.getElementById('cart-qty-' + id);
            const display = document.getElementById('cart-qty-display-' + id);
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            input.value = val;
            display.textContent = val;
            document.getElementById('cart-update-' + id).submit();
        }

        // Keep cart open if there were redirect flashes (non-ajax)
        @if(session('success') && !request()->ajax())
            // Only auto-toggle if not specifically disabled by user preference
            // toggleCart(); 
        @endif

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container') || createToastContainer();
            const toast = document.createElement('div');
            toast.className = `s-toast s-toast-${type}`;
            toast.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            document.body.appendChild(container);
            return container;
        }

        async function addToCart(productId, qty = 1) {
            try {
                const response = await fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ qty: qty })
                });

                const data = await response.json();
                if (data.success) {
                    showToast(data.message);
                    updateCartUI(data);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showToast('Could not add to cart', 'error');
            }
        }

        function updateCartUI(data) {
            // Update badge
            const badge = document.getElementById('cart-badge-count');
            if (badge) {
                badge.textContent = data.cart_count;
                badge.style.display = data.cart_count > 0 ? '' : 'none';
            }

            // Update sidebar content
            const container = document.getElementById('cart-sidebar-container');
            if (container && data.cart_html) {
                container.innerHTML = data.cart_html;
            }
        }

        // === Live Search ===
        (function() {
            const input = document.getElementById('searchInput');
            const catSelect = document.getElementById('searchCategory');
            const resultsBox = document.getElementById('searchResults');
            let debounceTimer;

            if (!input || !resultsBox) return;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const q = this.value.trim();
                if (q.length < 2) { resultsBox.style.display = 'none'; return; }
                debounceTimer = setTimeout(() => doSearch(q), 300);
            });

            if (catSelect) {
                catSelect.addEventListener('change', function() {
                    const q = input.value.trim();
                    if (q.length >= 2) doSearch(q);
                });
            }

            function doSearch(q) {
                const cat = catSelect ? catSelect.value : '';
                const url = `/api/search?q=${encodeURIComponent(q)}${cat ? '&category=' + cat : ''}`;
                fetch(url)
                    .then(r => r.json())
                    .then(products => {
                        if (products.length === 0) {
                            resultsBox.innerHTML = '<div class="s-searchEmpty">No products found</div>';
                        } else {
                            resultsBox.innerHTML = products.map(p => `
                                <a href="${p.url}" class="s-searchItem">
                                    <img src="${p.image}" alt="${p.name}" class="s-searchItemImg" />
                                    <div>
                                        <p class="s-searchItemName">${p.name}</p>
                                        <p class="s-searchItemCat">${p.category}</p>
                                    </div>
                                    <span class="s-searchItemPrice">$${p.price}</span>
                                </a>
                            `).join('');
                        }
                        resultsBox.style.display = 'block';
                    })
                    .catch(() => { resultsBox.style.display = 'none'; });
            }

            // Close on click outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.s-searchWrap')) {
                    resultsBox.style.display = 'none';
                }
            });
        })();
    </script>
</body>
</html>
