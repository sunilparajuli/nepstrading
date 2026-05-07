<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Primary Meta Tags -->
    @php 
        $appName = \App\Models\SiteSetting::getValue('app_name', 'Nepstrading'); 
        $siteLogoPath = \App\Models\SiteSetting::getValue('site_logo');
        $siteLogo = $siteLogoPath ? asset('storage/' . $siteLogoPath) : asset('favicon.ico');
        $sections = View::getSections();
        $metaTitle = $sections['meta_title'] ?? $appName;
        $metaDesc = $sections['meta_description'] ?? 'Your authentic Indian and Nepali store, delivering fresh grocery and quality goods right to your door.';
    @endphp
    <title>{{ $metaTitle }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <meta name="title" content="{{ $metaTitle }}">
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="@yield('meta_keywords', 'indian groceries, nepali spices, authentic nepalese, indian delivery, ' . strtolower($appName) . ', online shopping')">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $siteLogo }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:image" content="@yield('og_image', $siteLogo)">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $metaTitle }}">
    <meta property="twitter:description" content="{{ $metaDesc }}">
    <meta property="twitter:image" content="@yield('og_image', $siteLogo)">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Organization",
      "name": "{{ $appName }}",
      "url": "{{ url('/') }}",
      "logo": "{{ $siteLogo }}",
      "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "{{ \App\Models\SiteSetting::getValue('footer_phone', '0390189566') }}",
        "contactType": "customer service"
      }
    }
    </script>
    @stack('seo_schema')
    <!-- Tailwind CSS (for base utilities if needed, though we will port the exact CSS) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @php
        $sitePrimaryColor = \App\Models\SiteSetting::getValue('primary_color', '#5eba7d');
        
        // Simple Hex to HSL Conversion
        if (!function_exists('hexToHslComponents')) {
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
        }

        $hsl = hexToHslComponents($sitePrimaryColor);
        $primaryVar = implode(', ', $hsl);
        
        // For darker variant (promo bar etc) - just reduce L by 5-10%
        $lVal = (int)str_replace('%', '', $hsl[2]);
        $promoVar = $hsl[0] . ', ' . $hsl[1] . ', ' . max(0, $lVal - 7) . '%';
        
        $footerBgVar = $hsl[0] . ', ' . max(10, (int)str_replace('%', '', $hsl[1]) - 20) . '%, 8%';

        // Payment Methods
        $paypalEnabled = \App\Models\SiteSetting::getValue('payment_paypal_enabled', '0');
        $bankEnabled = \App\Models\SiteSetting::getValue('payment_bank_enabled', '0');
    @endphp
    <style>
        :root {
            /* Creamy Aesthetic Palette */
            --bg: 40 30% 98%; /* #FDFBF7 */
            --fg: 20 20% 15%; /* Deep Charcoal Brown */
            --muted: 40 25% 95%; /* Soft Cream */
            --muted-fg: 20 10% 45%;
            --border: 35 20% 90%;
            
            --primary: 35 40% 45%; /* Warm Tan/Camel */
            --primary-fg: 40 30% 98%;
            
            --accent: 35 50% 92%; /* Light Sand */
            --accent-fg: 35 40% 25%;
            
            --sale: 15 60% 50%; /* Soft Terracotta */
            
            --radius: 1rem;
            --promo-bg: {{ $promoVar }};
            --promo-fg: 0, 0%, 100%;
            --footer-bg: {{ $footerBgVar }};
            --footer-fg: 210, 20%, 98%;
            --footer-heading: 42, 78%, 61%; /* Warm gold #EBBE4D */
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

        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        /* --- React Inline Styles Ported to CSS --- */
        .s-announcementBar {
            display: flex; align-items: center; justify-content: center; gap: 16px;
            flex-wrap: wrap; background: hsl(var(--primary)); color: hsl(var(--primary-fg));
            font-size: 14px; padding: 8px 16px; text-align: center;
        }
        .s-navBar {
            background: hsl(var(--primary));
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
            max-width: 150px; text-overflow: ellipsis;
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
            color: hsl(var(--primary-fg));
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
            font-size: 14px; font-weight: 500; padding: 12px 16px; border: none;
            background: transparent; cursor: pointer; white-space: nowrap;
            color: hsl(var(--fg)); transition: background 0.2s, color 0.2s; text-decoration: none; display: flex; align-items: center; gap: 4px;
        }
        .s-navLink:hover { background: hsl(var(--muted)); color: hsl(var(--primary)); }
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

        .m-footer {
            background: hsl(var(--primary)); color: white; padding: 64px 0 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .m-footerInner {
            max-width: 1200px; margin: 0 auto; padding: 0 24px 48px;
            display: grid; grid-template-columns: 1.2fr 0.8fr 1fr 1.2fr; gap: 48px;
        }
        @media (max-width: 1024px) { .m-footerInner { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 640px) { .m-footerInner { grid-template-columns: 1fr; gap: 32px; } }
        
        .m-footerLogo {
            font-family: 'DM Serif Display', serif; font-size: 28px; color: #FFFFFF; margin: 0 0 20px;
            font-style: italic; letter-spacing: -0.01em;
        }
        .m-footerText { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.7; margin-bottom: 24px; }
        .m-footerHeading { 
            font-size: 15px; font-weight: 700; color: #facc15; 
            margin: 0 0 24px; text-transform: uppercase; letter-spacing: 0.05em; 
        }
        .m-footerLink { 
            color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 8px;
            margin-bottom: 14px; font-size: 14px; transition: all 0.2s; 
        }
        .m-footerLink:hover { color: hsl(var(--footer-heading)); transform: translateX(4px); }
        
        .m-contactItem {
            display: flex; gap: 12px; margin-bottom: 16px; font-size: 14px; color: rgba(255,255,255,0.7);
        }
        .m-contactIcon { color: hsl(var(--footer-heading)); flex-shrink: 0; margin-top: 2px; }
        
        .m-socialLinks { display: flex; gap: 12px; }
        .m-socialBtn {
            width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center; color: white;
            transition: all 0.3s;
        }
        .m-socialBtn:hover { background: hsl(var(--primary)); transform: translateY(-3px); }
        
        .m-newsletter p { font-size: 14px; color: rgba(255,255,255,0.7); margin-bottom: 20px; line-height: 1.6; }
        .m-newsletterForm { 
            background: rgba(255,255,255,0.05); border-radius: 8px; padding: 4px;
            display: flex; gap: 4px; border: 1px solid rgba(255,255,255,0.1);
        }
        .m-newsletterInput { 
            background: transparent; border: none; color: white; padding: 8px 12px; 
            font-size: 14px; flex: 1; outline: none;
        }
        .m-newsletterInput::placeholder { color: rgba(255,255,255,0.7); }
        .m-newsletterBtn { 
            background: hsl(var(--primary)); color: white; border: none; padding: 8px 16px;
            border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;
        }
        .m-newsletterBtn:hover { opacity: 0.9; }

        .m-footerBottom {
            background: rgba(0,0,0,0.15); padding: 24px 0;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .m-footerBottomInner {
            max-width: 1200px; margin: 0 auto; padding: 0 24px;
            display: flex; justify-content: space-between; align-items: center; gap: 20px;
        }
        @media (max-width: 640px) { 
            .m-footerBottomInner { flex-direction: column; text-align: center; } 
        }
        .m-copyright { font-size: 13px; color: rgba(255,255,255,0.5); margin: 0; }
        .m-paymentIcons { display: flex; align-items: center; gap: 12px; }
        .m-paymentIcon { height: 20px; opacity: 0.6; filter: grayscale(1); transition: opacity 0.2s; }
        .m-paymentIcon:hover { opacity: 1; filter: none; }
        .m-bankBadge {
            display: flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700;
            text-transform: uppercase; padding: 2px 6px; border-radius: 4px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            color: white; height: auto !important; opacity: 0.8;
        }
        .m-bankBadge:hover { opacity: 1; background: rgba(255,255,255,0.15); }

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
            border: none; cursor: pointer; background: hsl(var(--primary)); color: hsl(var(--primary-fg));
            transition: all 0.2s; margin-bottom: 16px; text-decoration: none;
        }
        .s-checkoutBtn:hover { 
            opacity: 0.9; 
            transform: translateY(-1px);
            box-shadow: 0 4px 12px hsla(var(--primary), 0.2);
        }
        
        .s-progressTrack { width: 100%; height: 6px; border-radius: 999px; background: hsl(var(--border)); }
        .s-progressFill { height: 6px; border-radius: 999px; background: hsl(var(--fg)); transition: width 0.3s; }
        
        .s-cartItem { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid hsl(var(--border)); }
        .s-cartItemImg { width: 56px; height: 56px; border-radius: 6px; object-fit: cover; flex-shrink: 0; }

        /* Back to Top */
        .s-backToTop {
            position: fixed; bottom: 32px; right: 32px; width: 44px; height: 44px;
            background: hsl(var(--primary)); color: white; border: none; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; opacity: 0; visibility: hidden; transform: translateY(20px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); z-index: 999; 
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
        }
        .s-backToTop.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .s-backToTop:hover { transform: translateY(-5px); background: hsl(var(--fg)); }

        /* Inquiry Modal */
        .s-modal {
            position: fixed; inset: 0; z-index: 2000;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .s-modal.show { opacity: 1; visibility: visible; }
        .s-modalBg { 
            position: absolute; inset: 0; background: rgba(15, 23, 42, 0.6); 
            backdrop-filter: blur(12px); 
        }
        .s-modalPanel {
            position: relative; width: 90%; max-width: 480px;
            background: white; border-radius: 24px; padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            transform: translateY(20px) scale(0.95); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .s-modal.show .s-modalPanel { transform: translateY(0) scale(1); }
        .s-modalIcon {
            width: 64px; height: 64px; background: hsl(var(--primary) / 0.1);
            color: hsl(var(--primary)); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
        }
        .s-modalTitle {
            font-family: 'DM Serif Display', serif; font-size: 26px;
            color: hsl(var(--fg)); text-align: center; margin-bottom: 16px;
        }
        .s-modalText {
            font-size: 16px; line-height: 1.7; color: hsl(var(--muted-fg));
            text-align: center; margin-bottom: 36px;
        }
        .s-modalBtn {
            width: 100%; padding: 16px; background: hsl(var(--primary));
            color: white; border: none; border-radius: 14px;
            font-weight: 700; font-size: 15px; cursor: pointer; transition: transform 0.2s, opacity 0.2s;
        }
        .s-modalBtn:hover { opacity: 0.95; transform: translateY(-2px); }
        .s-modalBtn:active { transform: translateY(0); }

        /* Qty Loading State */
        .s-qtyLoading { opacity: 0.6; pointer-events: none; }
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
        .s-overlay { position: fixed; inset: 0; z-index: 1050; display: none; }
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
        .s-closeBtn { padding: 4px; background: transparent; border: none; cursor: pointer; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: hsl(var(--fg)); }
        .s-closeBtn:hover { background: hsl(var(--muted)); }
        
        /* Mobile Menu */
        .s-mobileMenuBtn {
            display: none; padding: 8px; border-radius: 8px; background: transparent; border: none; cursor: pointer; color: hsl(var(--fg)); margin-right: 12px;
        }
        .s-mobileMenuBtn:hover { background: hsl(var(--muted)); }
        @media (max-width: 768px) {
            .s-mobileMenuBtn { display: flex; align-items: center; justify-content: center; }
            .s-navLinks { display: none; }
        }
        .s-mobileDrawer { position: fixed; inset: 0; z-index: 1050; display: none; }
        .s-mobileDrawer.open { display: block; }
        .s-mobileDrawerBg { position: absolute; inset: 0; background: hsla(210, 11%, 15%, 0.5); cursor: pointer; }
        .s-mobileDrawerPanel {
            position: absolute; left: 0; top: 0; bottom: 0; width: 300px; max-width: 85%;
            background: hsl(var(--bg)); border-right: 1px solid hsl(var(--border));
            transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column; overflow-y: auto;
        }
        .s-mobileDrawer.open .s-mobileDrawerPanel { transform: translateX(0); }
        .s-mobileDrawerHeader { display: flex; align-items: center; justify-content: space-between; padding: 16px; border-bottom: 1px solid hsl(var(--border)); }
        .s-mobileDrawerLink {
            display: flex; align-items: center; gap: 12px; padding: 16px; border-bottom: 1px solid hsl(var(--border));
            font-size: 15px; font-weight: 500; color: hsl(var(--fg)); text-decoration: none; transition: background 0.2s;
        }
        .s-mobileDrawerLink:hover { color: hsl(var(--primary)); background: hsl(var(--muted)); }

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
            max-height: 85vh;
            overflow-y: auto;
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
            content: "";
            position: absolute;
            bottom: 0px;
            left: 0px;
            width: 40px;
            height: 2px ;
            background-color: hsl(var(--primary)) ;
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
            height: 120px; 
            object-fit: cover; 
            border-radius: 8px;
            background: #f8fafc;
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

        /* Swiper Custom Navigation */
        .swiper-button-next, .swiper-button-prev {
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            color: #15803D;
            transition: all 0.3s;
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
        }
        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: #15803D;
            color: white;
            transform: scale(1.1);
        }
        /* Chat Widget Styles */
        #chatbot-widget {
            position: fixed; bottom: 90px; right: 24px; width: 350px; height: 500px;
            background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: none; flex-direction: column; z-index: 2000; overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform: translateY(20px); opacity: 0;
        }
        #chatbot-widget.show { display: flex; transform: translateY(0); opacity: 1; }
        .chat-header {
            background: hsl(var(--primary)); color: white; padding: 16px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .chat-messages { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px; background: #f8fafc; }
        .chat-bubble {
            max-width: 80%; padding: 10px 14px; border-radius: 12px; font-size: 14px; line-height: 1.5;
        }
        .chat-bubble.bot { background: white; color: hsl(var(--fg)); align-self: flex-start; border-bottom-left-radius: 2px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .chat-bubble.user { background: hsl(var(--primary)); color: white; align-self: flex-end; border-bottom-right-radius: 2px; }
        .chat-input-area { padding: 12px; border-top: 1px solid #eee; display: flex; gap: 8px; background: white; }
        .chat-input { flex: 1; border: 1px solid #ddd; border-radius: 20px; padding: 8px 16px; font-size: 14px; outline: none; }
        .chat-send { background: hsl(var(--primary)); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .chat-btn-float {
            position: fixed; bottom: 24px; right: 24px; width: 56px; height: 56px;
            background: hsl(var(--primary)); color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 2001; transition: transform 0.2s;
        }
        .chat-btn-float:hover { transform: scale(1.1); }
        .chat-action-btn {
            display: block; width: 100%; padding: 8px; margin-top: 8px; background: white;
            border: 1px solid hsl(var(--primary)); color: hsl(var(--primary));
            border-radius: 6px; font-weight: 600; text-align: center; text-decoration: none; font-size: 12px;
            transition: all 0.2s;
        }
        .chat-action-btn:hover { background: hsl(var(--primary)); color: white; }
        .variation-options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }
        .var-option-card {
            border: 2px solid hsl(var(--border));
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }
        .var-option-card:hover {
            border-color: hsl(var(--primary));
            background: hsl(var(--primary) / 0.05);
        }
        .var-option-card.selected {
            border-color: hsl(var(--primary));
            background: hsl(var(--primary));
            color: white !important;
        }
        .var-option-card .price {
            font-weight: 700;
            display: block;
            margin-top: 4px;
        }
        .var-option-card.selected * {
            color: white !important;
        }
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
                        <button class="s-mobileMenuBtn" onclick="toggleMobileMenu()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                        </button>
                        <a href="{{ url('/') }}" class="s-logo" style="color: hsl(var(--fg));">
                            @php $siteLogo = \App\Models\SiteSetting::getValue('site_logo'); @endphp
                            @if($siteLogo)
                                <img src="{{ asset('storage/' . $siteLogo) }}" alt="Nepstrading" style="height: 40px; width: auto; object-fit: contain;">
                            @else
                                Nepstrading
                            @endif
                        </a>
                        <div class="s-searchWrap hidden md:flex" style="position: relative;">
                            <select id="searchCategory" class="s-searchSelect">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Category::whereNull('parent_id')->with('children.children')->orderBy('name')->get() as $pCat)
                                    <option value="{{ $pCat->id }}" class="font-bold">{{ $pCat->name }}</option>
                                    @foreach($pCat->children as $cCat)
                                        <option value="{{ $cCat->id }}">&nbsp;&nbsp;— {{ $cCat->name }}</option>
                                        @foreach($cCat->children as $gcCat)
                                            <option value="{{ $gcCat->id }}">&nbsp;&nbsp;&nbsp;&nbsp;—— {{ $gcCat->name }}</option>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="s-searchInputWrap">
                                <input class="s-searchInput" placeholder="Search for products..." autocomplete="off" />
                                <span class="s-searchIcon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </span>
                            </div>
                            <!-- Search Results Megamenu -->
                            <div class="s-searchResults s-searchMega" style="display: none;"></div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-left: auto;">
                            @auth
                                <a href="{{ route('wishlist.index') }}" class="s-iconBtn" title="Wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                                    @if($wishlistCount > 0)
                                        <span class="s-cartBadge">{{ $wishlistCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('customer.dashboard') }}" class="s-iconBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="s-iconBtn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </a>
                            @endauth
                            <button type="button" class="s-iconBtn" onclick="toggleCart()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                                <span id="cart-badge-count" class="s-cartBadge" style="{{ (session()->has('cart') && count(session('cart')) > 0) ? '' : 'display:none;' }}">
                                    {{ session()->has('cart') ? count(session('cart')) : 0 }}
                                </span>
                            </button>
                        </div>
                    </div>
                    <!-- Mobile Search Row -->
                    <div class="md:hidden pb-3 px-4">
                        <div class="s-searchWrap" style="position: relative;">
                            <div class="s-searchInputWrap">
                                <input class="s-searchInput" style="border-radius: 6px;" placeholder="Search for products..." autocomplete="off" />
                                <span class="s-searchIcon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </span>
                            </div>
                            <div class="s-searchResults s-searchMega" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="s-navLinks" style="background: hsl(var(--card-bg)); box-shadow: var(--shadow-sm); padding: 0 16px; margin: 0;">
                        <a href="{{ url('/') }}" class="s-navLink">Home</a>
                        <a href="/page/payment-method" class="s-navLink">Payment Method</a>
                        <a href="{{ route('products.index') }}" class="s-navLink highlight">All Products</a>

                        <!-- Comprehensive Categories Dropdown -->
                        <div class="s-navDropdown">
                            <button class="s-navLink">
                                Categories
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="s-megaMenu">
                                <div class="s-megaInner py-8 px-10 grid grid-cols-5 gap-y-10 gap-x-12">
                                    @php 
                                        $allNavCategories = \App\Models\Category::with('children')->whereNull('parent_id')->orderBy('name')->get(); 
                                    @endphp
                                    @foreach($allNavCategories as $navCat)
                                        <div class="flex flex-col gap-3">
                                            <a href="{{ route('categories.show', $navCat) }}" class="font-extrabold text-[#111827] hover:text-[hsl(var(--primary))] text-[13px] uppercase tracking-widest block border-b-2 border-gray-100 pb-2 mb-1">
                                                {{ $navCat->name }}
                                            </a>
                                            @if($navCat->children->count() > 0)
                                                <div class="flex flex-col gap-2">
                                                    @foreach($navCat->children->take(6) as $sub)
                                                        <a href="{{ route('categories.show', $sub) }}" class="text-[13px] text-gray-500 hover:text-[hsl(var(--primary))] hover:translate-x-1 transition-all duration-200">
                                                            {{ $sub->name }}
                                                        </a>
                                                    @endforeach
                                                    @if($navCat->children->count() > 6)
                                                        <a href="{{ route('categories.show', $navCat) }}" class="text-[11px] font-bold text-[hsl(var(--primary))] hover:underline mt-1">
                                                            View All {{ $navCat->children->count() }} Items
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

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
            <footer class="m-footer">
                <div class="m-footerInner">
                    <div>
                        <h3 class="m-footerLogo">
                            @php $siteLogo = \App\Models\SiteSetting::getValue('site_logo'); @endphp
                            @if($siteLogo)
                                <img src="{{ asset('storage/' . $siteLogo) }}" alt="Nepstrading" style="height: 48px; object-fit: contain;">
                            @else
                                <span style="font-style: italic;">Nepstrading</span>
                            @endif
                        </h3>
                        <p class="m-footerText">{{ \App\Models\SiteSetting::getValue('footer_about_text', 'Premium Authentic Indian and Nepali spices, snacks, and daily essentials. Quality you can trust, delivered to your door.') }}</p>
                        <div class="m-socialLinks">
                            @php 
                                $fbUrl = \App\Models\SiteSetting::getValue('footer_facebook_url', '#');
                                $igUrl = \App\Models\SiteSetting::getValue('footer_instagram_url', '#');
                                $ytUrl = \App\Models\SiteSetting::getValue('footer_youtube_url', '#');
                            @endphp
                            @if($fbUrl && $fbUrl != '#')
                            <a href="{{ $fbUrl }}" target="_blank" class="m-socialBtn">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.56v14.9c0 1.35-1.15 2.5-2.56 2.5H2.56C1.15 21.96 0 20.81 0 19.46V4.56C0 3.2 1.15 2.05 2.56 2.05h18.88C22.85 2.05 24 3.2 24 4.56zM8.5 19v-7.3H6v7.3h2.5zm-1.25-8.3c.87 0 1.42-.58 1.42-1.3 0-.74-.53-1.32-1.38-1.32-.84 0-1.4.58-1.4 1.32 0 .72.55 1.3 1.36 1.3zm12.33 8.3v-4c0-2.14-1.14-3.14-2.67-3.14-1.22 0-1.78.68-2.08 1.16v-.99h-2.5c.03.7 0 7.02 0 7.02h2.5v-3.92c0-.2.02-.42.08-.57.17-.43.56-.88 1.22-.88.86 0 1.2.66 1.2 1.63V19h2.5z"/></svg>
                            </a>
                            @endif
                            @if($igUrl && $igUrl != '#')
                            <a href="{{ $igUrl }}" target="_blank" class="m-socialBtn">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm3.975-9.658a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            @endif
                            @if($ytUrl && $ytUrl != '#')
                            <a href="{{ $ytUrl }}" target="_blank" class="m-socialBtn">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h4 class="m-footerHeading">Shop Categories</h4>
                        @foreach(\App\Models\Category::whereNull('parent_id')->limit(5)->get() as $fCat)
                            <a href="{{ route('categories.show', $fCat) }}" class="m-footerLink">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                {{ $fCat->name }}
                            </a>
                        @endforeach
                    </div>

                    <div>
                        <h4 class="m-footerHeading">Useful Links</h4>
                        <a href="/page/payment-method" class="m-footerLink">Payment Method</a>
                        <a href="/page/about-us" class="m-footerLink">About Us</a>
                        <a href="/page/contact" class="m-footerLink">Contact Us</a>
                        <a href="/page/delivery-info" class="m-footerLink">Shipping Info</a>
                        <a href="/page/returns-policy" class="m-footerLink">Returns Policy</a>
                        <a href="/page/privacy-policy" class="m-footerLink">Privacy Policy</a>
                    </div>

                    <div class="m-newsletter">
                        <h4 class="m-footerHeading">Contact & Newsletter</h4>
                        <div class="m-contactItem">
                            <svg class="m-contactIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ \App\Models\SiteSetting::getValue('footer_address', '123 Market St, Sydney NSW 2000') }}</span>
                        </div>
                        <div class="m-contactItem">
                            <svg class="m-contactIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>{{ \App\Models\SiteSetting::getValue('footer_phone', '+61 4XX XXX XXX') }}</span>
                        </div>
                        <div class="m-contactItem">
                            <svg class="m-contactIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <span>order@nepstrading.com.au</span>
                        </div>
                        <form action="#" method="POST" class="m-newsletterForm" onsubmit="event.preventDefault(); alert('Successfully Subscribed!');">
                            <input type="email" placeholder="Email address" class="m-newsletterInput" required>
                            <button type="submit" class="m-newsletterBtn">Join</button>
                        </form>
                    </div>
                </div>

                <div class="m-footerBottom">
                    <div class="m-footerBottomInner">
                        <p class="m-copyright">
                            &copy; {{ date('Y') }} {{ \App\Models\SiteSetting::getValue('footer_copyright_text', 'Nepstrading. Built for Excellence.') }} 
                            | <span style="margin-left: 5px; opacity: 0.8;">Developed by </span>
                            <a href="https://tinker.com.np" target="_blank" style="background: white; color: #1e3a8a; padding: 3px 10px; border-radius: 6px; font-weight: 800; text-decoration: none; display: inline-block; margin-left: 5px; font-size: 11px; letter-spacing: 0.05em; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                tinker.com.np
                            </a>
                        </p>
                        <div class="m-paymentIcons">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="m-paymentIcon" alt="Visa">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="m-paymentIcon" alt="Mastercard">
                            @if($paypalEnabled)
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="m-paymentIcon" alt="PayPal">
                            @endif
                            @if($bankEnabled)
                                <div class="m-paymentIcon m-bankBadge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 6l7-3 7 3"/><path d="M4 10v11"/><path d="M20 10v11"/><path d="M8 14v3"/><path d="M12 14v3"/><path d="M16 14v3"/></svg>
                                    Bank
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </footer>

            <!-- Back to Top -->
            <button id="backToTop" class="s-backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
            </button>
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

        <!-- Mobile Menu Drawer -->
        <div id="mobileDrawer" class="s-mobileDrawer">
            <div class="s-mobileDrawerBg" onclick="toggleMobileMenu()"></div>
            <div class="s-mobileDrawerPanel">
                <div class="s-mobileDrawerHeader">
                    <span style="font-family: 'DM Serif Display', serif; font-size: 20px; font-weight: 500; font-style: italic; color: hsl(var(--primary));">Menu</span>
                    <button class="s-closeBtn" onclick="toggleMobileMenu()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div style="flex: 1; overflow-y: auto;">
                    <a href="{{ url('/') }}" class="s-mobileDrawerLink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" class="s-mobileDrawerLink" style="color: hsl(var(--accent));">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        All Products
                    </a>
                    @foreach(\App\Models\Category::whereNull('parent_id')->orderBy('name')->get() as $navCat)
                        <a href="{{ route('categories.show', $navCat) }}" class="s-mobileDrawerLink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                            {{ $navCat->name }}
                        </a>
                    @endforeach
                    @auth
                        <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('customer.dashboard') }}" class="s-mobileDrawerLink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Profile & Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="s-mobileDrawerLink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Profile & Login
                        </a>
                    @endauth
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

        function toggleMobileMenu() {
            const drawer = document.getElementById('mobileDrawer');
            if (drawer.classList.contains('open')) {
                drawer.classList.remove('open');
                document.body.style.overflow = '';
            } else {
                drawer.classList.add('open');
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
            const searchWraps = document.querySelectorAll('.s-searchWrap');
            let debounceTimer;

            searchWraps.forEach(wrap => {
                const input = wrap.querySelector('.s-searchInput');
                const catSelect = wrap.querySelector('.s-searchSelect');
                const resultsBox = wrap.querySelector('.s-searchResults');

                if (!input || !resultsBox) return;

                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const q = this.value.trim();
                    if (q.length < 2) { resultsBox.style.display = 'none'; return; }
                    debounceTimer = setTimeout(() => doSearch(q, catSelect, resultsBox), 300);
                });

                if (catSelect) {
                    catSelect.addEventListener('change', function() {
                        const q = input.value.trim();
                        if (q.length >= 2) doSearch(q, catSelect, resultsBox);
                    });
                }
            });

            function doSearch(q, catSelect, resultsBox) {
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
                    document.querySelectorAll('.s-searchResults').forEach(box => {
                        box.style.display = 'none';
                    });
                }
            });
        })();
    </script>
@push('seo_schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Organization",
  "name": "Nepstrading",
  "url": "{{ url('/') }}",
  "logo": "{{ \App\Models\SiteSetting::getValue('site_logo') ? asset('storage/' . \App\Models\SiteSetting::getValue('site_logo')) : asset('images/logo.png') }}",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+61-XXXX-XXXX",
    "contactType": "customer service"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Nepstrading",
  "url": "{{ url('/') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/products?search={search_term_string}') }}",
    "query-input": "required name=search_term_string"
  }
}
</script>
@endpush
        <!-- Inquiry Modal -->
        <div id="inquiryModal" class="s-modal">
            <div class="s-modalBg" onclick="closeInquiryModal()"></div>
            <div class="s-modalPanel">
                <div class="s-modalIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h3 class="s-modalTitle">Ordering Information</h3>
                <div id="inquiryModalText" class="s-modalText"></div>
                <button class="s-modalBtn" onclick="closeInquiryModal()">Understood</button>
            </div>
        </div>
        <!-- Variations Modal -->
        <div id="variationModal" class="s-modal">
            <div class="s-modalBg" onclick="closeVariationModal()"></div>
            <div class="s-modalPanel">
                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 14px; text-transform: uppercase; color: hsl(var(--muted-fg)); letter-spacing: 0.1em; margin-bottom: 4px;">Available Choices</div>
                    <h3 class="s-modalTitle" id="varModalTitle" style="margin: 0;">Select Options</h3>
                </div>
                <div id="variationOptions" class="variation-options-grid">
                    <!-- Dynamic Options -->
                </div>
                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button class="s-modalBtn" style="background: #f3f4f6; color: #4b5563; flex: 1;" onclick="closeVariationModal()">Cancel</button>
                    <button class="s-modalBtn" id="addVarToCartBtn" style="flex: 2;" disabled onclick="submitVariationAddToCart()">Add to Cart</button>
                </div>
            </div>
        </div>

        <!-- AI Chatbot Widget -->
    <div id="chatbot-widget">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 8px; height: 8px; background: #4ade80; border-radius: 50%;"></div>
                <div>
                    <div style="font-weight: 700; line-height: 1;">Shop Assistant</div>
                    <div style="font-size: 10px; opacity: 0.8; margin-top: 2px;">Product Inquiry</div>
                </div>
            </div>
            <button onclick="toggleChat()" style="background: none; border: none; color: white; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="chat-messages" id="chat-messages">
            <div class="chat-bubble bot">
                Namaste! I'm your Nepstrading Assistant. How can I help you find what you need today?
            </div>
        </div>
        <form class="chat-input-area" onsubmit="sendChatMessage(event)">
            <input type="text" id="chat-user-input" class="chat-input" placeholder="Inquire about a product (e.g. Soya)..." autocomplete="off">
            <button type="submit" class="chat-send">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            </button>
        </form>
    </div>

    <div class="chat-btn-float" onclick="toggleChat()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        function toggleChat() {
            const widget = document.getElementById('chatbot-widget');
            widget.classList.toggle('show');
            if (widget.classList.contains('show')) {
                const container = document.getElementById('chat-messages');
                container.scrollTop = container.scrollHeight;
            }
        }

        // Persistent History Logic
        function saveToHistory(text, sender, actions = null) {
            let history = JSON.parse(localStorage.getItem('nepstrading_chat_history') || '[]');
            history.push({ text, sender, actions });
            if (history.length > 20) history.shift(); // Keep last 20
            localStorage.setItem('nepstrading_chat_history', JSON.stringify(history));
        }

        function loadHistory() {
            const history = JSON.parse(localStorage.getItem('nepstrading_chat_history') || '[]');
            history.forEach(item => {
                addMessage(item.text, item.sender, item.actions, null, false); // false = don't save again
            });
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chat-user-input');
            const message = input.value.trim();
            if (!message) return;

            input.value = '';
            addMessage(message, 'user');

            // Loading indicator
            const loadingId = 'loading-' + Date.now();
            addMessage('Searching store...', 'bot', null, loadingId);

            try {
                const response = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                document.getElementById(loadingId).remove();

                if (response.ok) {
                    addMessage(data.message, 'bot', data.actions);
                } else {
                    addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                }
            } catch (error) {
                if (document.getElementById(loadingId)) document.getElementById(loadingId).remove();
                addMessage('Connection error. Please check your internet.', 'bot');
            }
        }

        function addMessage(text, sender, actions = null, id = null, save = true) {
            const container = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.className = `chat-bubble ${sender}`;
            if (id) div.id = id;
            div.textContent = text;
            
            if (actions && actions.length > 0) {
                actions.forEach(action => {
                    const btn = document.createElement('a');
                    btn.className = 'chat-action-btn';
                    if (action.type === 'product') {
                        if (action.is_variable) {
                            btn.href = 'javascript:void(0)';
                            btn.onclick = () => openVariationModal(action.id, action.name);
                            btn.textContent = (action.name || 'Product') + ' (Select Sizes)';
                        } else {
                            btn.href = `/products/${action.slug}`;
                            btn.textContent = (action.name || 'Product') + ' (View)';
                        }
                    } else if (action.type === 'category') {
                        btn.href = `/categories/${action.slug}`;
                        btn.textContent = 'Explore Category';
                    } else if (action.type === 'contact') {
                        btn.href = `tel:${action.phone}`;
                        btn.textContent = 'Call Store: ' + action.phone;
                    }
                    div.appendChild(btn);
                });
            }

            container.appendChild(div);
            container.scrollTop = container.scrollHeight;

            if (save && sender !== 'loading') {
                saveToHistory(text, sender, actions);
            }
        }

        let selectedVariationId = null;
        let activeVariantProductId = null;

        window.openVariationModal = async function(productId, productName) {
            activeVariantProductId = productId;
            selectedVariationId = null;
            document.getElementById('varModalTitle').textContent = productName;
            const container = document.getElementById('variationOptions');
            const addBtn = document.getElementById('addVarToCartBtn');
            
            container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 20px;">Loading options...</div>';
            addBtn.disabled = true;
            document.getElementById('variationModal').classList.add('show');

            try {
                const response = await fetch(`/api/products/${productId}`);
                const data = await response.json();
                
                container.innerHTML = '';
                if (data.variations && data.variations.length > 0) {
                    data.variations.forEach(v => {
                        const card = document.createElement('div');
                        card.className = 'var-option-card';
                        card.innerHTML = `
                            <div style="font-size: 13px; font-weight: 600;">${v.formatted_attributes}</div>
                            <div class="price">$${v.price}</div>
                        `;
                        card.onclick = () => {
                            document.querySelectorAll('.var-option-card').forEach(c => c.classList.remove('selected'));
                            card.classList.add('selected');
                            selectedVariationId = v.id;
                            addBtn.disabled = false;
                        };
                        container.appendChild(card);
                    });
                } else {
                    container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: red;">No options available for this product.</div>';
                }
            } catch (error) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: red;">Error loading options.</div>';
            }
        };

        window.closeVariationModal = function() {
            document.getElementById('variationModal').classList.remove('show');
        };

        window.submitVariationAddToCart = async function() {
            if (!selectedVariationId || !activeVariantProductId) return;
            
            const addBtn = document.getElementById('addVarToCartBtn');
            const originalText = addBtn.textContent;
            addBtn.disabled = true;
            addBtn.textContent = 'Adding...';

            try {
                const response = await fetch(`/cart/${activeVariantProductId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ variation_id: selectedVariationId, qty: 1 })
                });

                const data = await response.json();
                if (data.success) {
                    showToast('Added to cart!');
                    closeVariationModal();
                    if (typeof updateCartUI === 'function') updateCartUI(data);
                }
            } catch (error) {
                showToast('Error adding to cart', 'error');
            } finally {
                addBtn.disabled = false;
                addBtn.textContent = originalText;
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            loadHistory();
            // Initialize Product Carousels
            const productSwipers = document.querySelectorAll('.product-swiper');
            productSwipers.forEach((swiperEl, index) => {
                const section = swiperEl.closest('section');
                if (!section) return; // Skip if no section parent

                const nextEl = section.querySelector('.swiper-button-next');
                const prevEl = section.querySelector('.swiper-button-prev');
                
                const desktopCols = parseInt(swiperEl.getAttribute('data-cols')) || 5;
                const tabletCols = Math.max(3, Math.min(desktopCols, 4));
                
                new Swiper(swiperEl, {
                    slidesPerView: 2,
                    spaceBetween: 16,
                    loop: true,
                    navigation: {
                        nextEl: nextEl,
                        prevEl: prevEl,
                    },
                    breakpoints: {
                        640: { slidesPerView: 2, spaceBetween: 20 },
                        768: { slidesPerView: tabletCols, spaceBetween: 20 },
                        1024: { slidesPerView: desktopCols, spaceBetween: 24 },
                    },
                    on: {
                        init: function() {
                            // Ensure buttons are visible if needed
                        }
                    }
                });
            });

            // Back to Top functionality
            const backToTop = document.getElementById('backToTop');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 400) backToTop.classList.add('show');
                else backToTop.classList.remove('show');
            });

            // Handle disabled cart messages
            window.showCartDisabledMessage = function(message) {
                const modal = document.getElementById('inquiryModal');
                const text = document.getElementById('inquiryModalText');
                if (!message) message = "This item is currently not available for online purchase.";
                text.innerText = message;
                modal.classList.add('show');
            };

            window.closeInquiryModal = function() {
                document.getElementById('inquiryModal').classList.remove('show');
            };

            window.updateCartQty = async function(id, delta) {
                const input = document.getElementById('cart-qty-' + id);
                if (!input) return;
                
                const itemRow = input.closest('.s-cartItem');
                let val = parseInt(input.value) + delta;
                if (val < 1) return; 

                if (itemRow) itemRow.classList.add('s-qtyLoading');

                try {
                    const response = await fetch(`/cart/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ qty: val, _method: 'PATCH' })
                    });

                    const data = await response.json();
                    if (data.success) {
                        updateCartUI(data);
                    }
                } catch (error) {
                    console.error('Error updating cart:', error);
                    showToast('Could not update cart', 'error');
                    if (itemRow) itemRow.classList.remove('s-qtyLoading');
                }
            };

            window.removeCartItem = async function(id) {
                const itemRow = document.querySelector(`.s-cartItem[data-id="${id}"]`) || document.getElementById('cart-qty-' + id)?.closest('.s-cartItem');
                if (itemRow) itemRow.classList.add('s-qtyLoading');

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
                        updateCartUI(data);
                    }
                } catch (error) {
                    console.error('Error removing item:', error);
                    showToast('Could not remove item', 'error');
                    if (itemRow) itemRow.classList.remove('s-qtyLoading');
                }
            };
        });
    </script>
</body>
</html>
