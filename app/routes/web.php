<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Main page - product list (this is the default redirect after login for regular users)
Route::get('/', [ProductController::class, 'index'])->name('home');

// Dashboard route alias for compatibility with auth tests
Route::get('/user-dashboard', fn () => redirect()->route('home'))->middleware(['auth', 'verified'])->name('dashboard');

// Products (public)
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Cart
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout
    Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin routes (auth + admin only)
Route::middleware(['auth', 'verified', 'admin'])->prefix('dashboard')->name('admin.')->group(function () {
    // Dashboard home
    Route::get('/', [AdminReportController::class, 'index'])->name('dashboard');

    // Product management (use ID instead of slug for admin)
    Route::resource('products', AdminProductController::class)->scoped([
        'product' => 'id',
    ]);

    // Order management (use ID for admin)
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order:id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order:id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

require __DIR__.'/settings.php';
