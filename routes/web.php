<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SitemapController;

Route::get('/', [App\Http\Controllers\HomeController::class , 'index'])->name('home');
Route::get('/admin-login', function () {
    $user = \App\Models\User::where('email', 'admin@example.com')->first();
    if ($user) {
        auth()->login($user, true);
        return redirect('/admin');
    }
    return 'User admin@example.com not found.';
});
Route::get('/sitemap.xml', [SitemapController::class , 'index']);

Route::get('/products', [ProductController::class , 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class , 'show'])->name('products.show');
Route::resource('categories', CategoryController::class)->only(['index', 'show']);

// Auth Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class , 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class , 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class , 'logout'])->name('logout');
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class , 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class , 'register']);

// Password Reset Routes
Route::get('forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])->name('password.update');

Route::get('/cart', [CartController::class , 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class , 'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class , 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class , 'remove'])->name('cart.remove');
Route::post('/cart/shipping', [CartController::class , 'setLocation'])->name('cart.shipping.set');
Route::get('/cart/shipping/auto', [CartController::class , 'autoDetectLocation'])->name('cart.shipping.auto');

// Coupon Routes
Route::post('/cart/coupon', [CouponController::class , 'apply'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CouponController::class , 'remove'])->name('cart.coupon.remove');

// Search API
Route::get('/api/search', [SearchController::class , 'search'])->name('api.search');

// Auth-protected Routes
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class , 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class , 'store'])->name('checkout.store');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class , 'store'])->name('reviews.store');

    // Customer Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/orders/{order}', [\App\Http\Controllers\CustomerController::class, 'showOrder'])->name('customer.orders.show');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class , 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class , 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class , 'destroy'])->name('wishlist.destroy');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class , 'index'])->name('dashboard');
    Route::get('orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class , 'downloadInvoice'])->name('orders.invoice');
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show', 'create']);
    Route::resource('attributes', App\Http\Controllers\Admin\AttributeController::class);
    Route::resource('attribute-terms', App\Http\Controllers\Admin\AttributeTermController::class);
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::resource('locations', App\Http\Controllers\Admin\LocationController::class);
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);

    // Coupons
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);

    // Reviews Moderation
    Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class , 'index'])->name('reviews.index');
    Route::put('reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class , 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class , 'destroy'])->name('reviews.destroy');

    // Tax Rates
    Route::get('tax', [App\Http\Controllers\Admin\TaxController::class , 'index'])->name('tax.index');
    Route::post('tax', [App\Http\Controllers\Admin\TaxController::class , 'store'])->name('tax.store');
    Route::put('tax/{taxRate}', [App\Http\Controllers\Admin\TaxController::class , 'update'])->name('tax.update');
    Route::delete('tax/{taxRate}', [App\Http\Controllers\Admin\TaxController::class , 'destroy'])->name('tax.destroy');

    // Inventory & Homepage
    Route::get('inventory', [App\Http\Controllers\Admin\InventoryController::class , 'index'])->name('inventory.index');
    Route::post('inventory/{product}', [App\Http\Controllers\Admin\InventoryController::class , 'update'])->name('inventory.update');
    // Homepage Layout
    Route::resource('homepage', App\Http\Controllers\Admin\HomepageController::class)->names('homepage');
    Route::post('homepage/reorder', [App\Http\Controllers\Admin\HomepageController::class , 'reorder'])->name('homepage.reorder');

    // Shipping
    Route::get('shipping', [App\Http\Controllers\Admin\ShippingController::class , 'index'])->name('shipping.index');
    Route::post('shipping/zones', [App\Http\Controllers\Admin\ShippingController::class , 'storeZone'])->name('shipping.zones.store');
    Route::delete('shipping/zones/{zone}', [App\Http\Controllers\Admin\ShippingController::class , 'destroyZone'])->name('shipping.zones.destroy');
    Route::post('shipping/zones/{zone}/rates', [App\Http\Controllers\Admin\ShippingController::class , 'storeRate'])->name('shipping.rates.store');
    Route::delete('shipping/rates/{rate}', [App\Http\Controllers\Admin\ShippingController::class , 'destroyRate'])->name('shipping.rates.destroy');
    Route::post('shipping/zones/{zone}/locations', [App\Http\Controllers\Admin\ShippingController::class , 'storeLocation'])->name('shipping.locations.store');
    Route::delete('shipping/locations/{location}', [App\Http\Controllers\Admin\ShippingController::class , 'destroyLocation'])->name('shipping.locations.destroy');

    // Settings
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class , 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\Admin\SettingController::class , 'update'])->name('settings.update');
});

// CMS Pages (catch-all, must be last)
Route::get('/page/{page}', [PageController::class , 'show'])->name('pages.show');