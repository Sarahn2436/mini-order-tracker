<?php

use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CustomerCheckoutController;

Route::redirect('/', '/customerLogin');

Route::middleware('customerGuest')->group(function () {
    Route::get('/customerLogin', [CustomerAuthController::class, 'showCustomerLogin'])->name('customer.login');
    Route::post('/customerLogin', [CustomerAuthController::class, 'customerLogin'])->name('customer.login.submit');

    Route::get('/customerRegister', [CustomerAuthController::class, 'showCustomerRegister'])->name('customer.register');
    Route::post('/customerRegister', [CustomerAuthController::class, 'Customerregister'])->name('customer.register.submit');
});

Route::middleware('guest')->group(function () {
    Route::get('/staff-login', [AuthController::class, 'showLogin'])->name('staff.login');
    Route::post('/staff-login', [AuthController::class, 'login'])->name('staff.login.submit');

    Route::get('/staff-register', [AuthController::class, 'showRegister'])->name('staff.register');
    Route::post('/staff-register', [AuthController::class, 'register'])->name('staff.register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [OrderController::class, 'showOrders'])->name('orders');

    Route::resource('customers', CustomerController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/customer-dashboard', [CustomerAuthController::class, 'CustomerDashboard'])->name('customer.dashboard');
    Route::get('/customer-basket', [CustomerAuthController::class, 'customerBasket'])->name('customer.basket');
    Route::post('/basket/add/{id}', [BasketController::class, 'add'])->name('basket.add');
    Route::delete('/basket/remove/{id}', [BasketController::class, 'remove'])->name('basket.remove');
    Route::post('/customer-logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::get('/product-view/{slug}', [ProductController::class, 'show'])->name('customer.product');
    Route::get('/customer-checkout', [CustomerCheckoutController::class, 'CustomerCheckout'])->name('customer.checkout');
    Route::post('/order/add', [OrderController::class, 'add'])->name('order.add');
    Route::get('/customerorders', [OrderController::class, 'index'])->name('customer.orders');
    Route::delete('/order/remove/{order}', [OrderController::class, 'remove'])->name('order.remove');
    Route::get('/customer-settings', [CustomerAuthController::class, 'accountSettings'])->name('customer.settings');
    Route::delete('/customer-settings/delete', [CustomerAuthController::class, 'deleteAccount'])->name('customer.account.delete');

});

Route::get('/customer-login', [CustomerAuthController::class, 'login'])->name('login');
