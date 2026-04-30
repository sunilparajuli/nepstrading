<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#fcb800',
                        admin_bg: '#f0f0f1',
                        sidebar: '#2c3338'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f0f0f1; }
        .sidebar-item:hover { background-color: #1d2327; color: #72aee6; }
        .sidebar-item.active { background-color: #2271b1; color: #fff; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-sidebar text-gray-300 flex-shrink-0 flex flex-col">
            <div class="p-4 text-white font-bold text-xl border-b border-gray-700 flex items-center space-x-2">
                <span class="bg-primary text-black px-2 py-1 rounded text-sm">Nepstrading</span>
                <span>Admin</span>
            </div>
            <nav class="flex-grow py-4 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span>📊</span> <span>Dashboard</span>
                </a>
                <div class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">WooCommerce</div>
                <a href="{{ route('admin.orders.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span>📦</span> <span>Orders</span>
                </a>
                <a href="{{ route('admin.customers.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <span>👥</span> <span>Customers</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <span>🎟️</span> <span>Coupons</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <span>⭐</span> <span>Reviews</span>
                </a>
                <div class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Products</div>
                <a href="{{ route('admin.products.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <span>🛍️</span> <span>All Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span>🏷️</span> <span>Categories</span>
                </a>
                <a href="{{ route('admin.attributes.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    <span>⚙️</span> <span>Attributes</span>
                </a>
                <a href="{{ route('admin.brands.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <span>🏷️</span> <span>Brands</span>
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                    <span>📊</span> <span>Inventory</span>
                </a>
                <div class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Content</div>
                <a href="{{ route('admin.pages.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <span>📄</span> <span>Pages</span>
                </a>
                <a href="{{ route('admin.shipping.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
                    <span>🚚</span> <span>Shipping</span>
                </a>
                <a href="{{ route('admin.tax.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.tax.*') ? 'active' : '' }}">
                    <span>💰</span> <span>Tax</span>
                </a>
                <a href="{{ route('admin.locations.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                    <span>📍</span> <span>Locations</span>
                </a>
                <a href="{{ route('admin.homepage.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">
                    <span>🏠</span> <span>Home Layout</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-item flex items-center px-6 py-3 space-x-3 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span>⚙️</span> <span>Settings</span>
                </a>
            </nav>
            <div class="p-4 border-t border-gray-700 text-xs text-gray-500">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }}
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10">
                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 lg:hidden">☰</button>
                    <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="/" target="_blank" class="text-sm text-blue-600 hover:underline">Visit Store</a>
                    <div class="flex items-center space-x-2 group">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 group-hover:bg-primary transition-colors">👤</div>
                        <span class="text-sm font-medium text-gray-700">Admin User</span>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ml-4 text-xs font-semibold text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded border border-red-100 transition-colors">
                            Logout
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-grow overflow-x-hidden overflow-y-auto p-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 shadow-sm" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
