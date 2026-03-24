<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\WishlistApiController;
use App\Http\Controllers\Api\ReviewApiController;
use App\Http\Controllers\Api\PageApiController;
use App\Http\Controllers\Api\SettingsApiController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// Products (public)
Route::get('/products', [ProductApiController::class, 'index'])->name('api.products.index');
Route::get('/products/{product}', [ProductApiController::class, 'show'])->name('api.products.show');

// Categories (public)
Route::get('/categories', [CategoryApiController::class, 'index'])->name('api.categories.index');
Route::get('/categories/{category:slug}', [CategoryApiController::class, 'show'])->name('api.categories.show');

// Reviews (public read)
Route::get('/products/{product}/reviews', [ReviewApiController::class, 'index'])->name('api.reviews.index');

// Pages (public)
Route::get('/pages', [PageApiController::class, 'index'])->name('api.pages.index');
Route::get('/pages/{page:slug}', [PageApiController::class, 'show'])->name('api.pages.show');

// Settings (public)
Route::get('/settings', [SettingsApiController::class, 'index'])->name('api.settings');

/*
|--------------------------------------------------------------------------
| Protected API Routes (require Sanctum token)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user/profile', [AuthApiController::class, 'profile']);
    Route::put('/user/profile', [AuthApiController::class, 'updateProfile']);
    Route::delete('/user/account', [AuthApiController::class, 'deleteAccount']);

    // Products (admin CRUD)
    Route::post('/products', [ProductApiController::class, 'store'])->name('api.products.store');
    Route::put('/products/{product}', [ProductApiController::class, 'update'])->name('api.products.update');
    Route::delete('/products/{product}', [ProductApiController::class, 'destroy'])->name('api.products.destroy');

    // Cart
    Route::get('/cart', [CartApiController::class, 'index'])->name('api.cart.index');
    Route::post('/cart/add', [CartApiController::class, 'add'])->name('api.cart.add');
    Route::put('/cart/{cartItem}', [CartApiController::class, 'update'])->name('api.cart.update');
    Route::delete('/cart/{cartItem}', [CartApiController::class, 'remove'])->name('api.cart.remove');
    Route::post('/cart/coupon', [CartApiController::class, 'applyCoupon'])->name('api.cart.coupon.apply');
    Route::delete('/cart/coupon', [CartApiController::class, 'removeCoupon'])->name('api.cart.coupon.remove');

    // Checkout / Orders
    Route::post('/checkout', [OrderApiController::class, 'store'])->name('api.checkout');
    Route::get('/orders', [OrderApiController::class, 'index'])->name('api.orders.index');
    Route::get('/orders/{order}', [OrderApiController::class, 'show'])->name('api.orders.show');
    Route::post('/orders/{order}/cancel', [OrderApiController::class, 'cancel'])->name('api.orders.cancel');

    // Wishlist
    Route::get('/wishlist', [WishlistApiController::class, 'index'])->name('api.wishlist.index');
    Route::post('/wishlist', [WishlistApiController::class, 'store'])->name('api.wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistApiController::class, 'destroy'])->name('api.wishlist.destroy');

    // Reviews (authenticated write)
    Route::post('/products/{product}/reviews', [ReviewApiController::class, 'store'])->name('api.reviews.store');
});
