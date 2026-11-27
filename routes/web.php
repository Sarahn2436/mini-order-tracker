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
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CouponController;

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
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::get('/coupons', [CouponController::class, 'index'])->name('Coupons');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::patch('/coupons/{coupon}/deactivate', [CouponController::class, 'deactivate'])->name('coupons.deactivate');


    Route::resource('customers', CustomerController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/customer-dashboard', [CustomerAuthController::class, 'CustomerDashboard'])->name('customer.dashboard');
    Route::get('/customer-basket', [CustomerAuthController::class, 'customerBasket'])->name('customer.basket');
    Route::post('/basket/add/{id}', [BasketController::class, 'add'])->name('basket.add');
    Route::delete('/basket/remove/{id}', [BasketController::class, 'remove'])->name('basket.remove');
    Route::post('/customer-logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
    Route::get('/product-view/{slug}', [ProductController::class, 'show'])->name('customer.product');
    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
    Route::get('/customer-checkout', [CustomerCheckoutController::class, 'CustomerCheckout'])->name('customer.checkout');
    Route::post('/coupon/apply', [CustomerCheckoutController::class, 'applyCoupon'])->name('coupon.apply');
    Route::post('/order/add', [OrderController::class, 'add'])->name('order.add');
    Route::get('/customerorders', [OrderController::class, 'index'])->name('customer.orders');
    Route::delete('/order/remove/{order}', [OrderController::class, 'remove'])->name('order.remove');
    Route::patch('/orders/bulk-status', [OrderController::class, 'bulkUpdateStatus'])->name('orders.bulkUpdateStatus');
    Route::get('/customer-settings', [CustomerAuthController::class, 'accountSettings'])->name('customer.settings');
    Route::delete('/customer-settings/delete', [CustomerAuthController::class, 'deleteAccount'])->name('customer.account.delete');
    Route::put('/customer-settings/update', [CustomerAuthController::class, 'updateAccount'])->name('customer.account.update');


});

Route::get('/chat/messages/{sessionId}', [ChatController::class, 'getMessages']);
Route::post('/chat/send', [ChatController::class, 'storeMessage']);
Route::get('/chat/rooms', [ChatController::class, 'getActiveRooms']);

Route::get('/staff/chats', function () {
    return view('layouts.Staff_view.chats'); 
})->middleware('auth')->name('chat');

Route::get('/customer-login', [CustomerAuthController::class, 'login'])->name('login');

Route::post('/stripe/checkout', [CustomerCheckoutController::class, 'add'])->name('stripe.checkout');

Schedule::command('orders:advance-status')->everyMinute();



Route::get('lang/{locale}', function ($locale, \Illuminate\Http\Request $request) {
    if (in_array($locale, ['en', 'es', 'fr'])) {
        session(['locale' => $locale]);

        if (\Illuminate\Support\Facades\Auth::check()) {
            /** @var \App\Models\User $user */
            $user = \Illuminate\Support\Facades\Auth::user();
            $user->update(['locale' => $locale]);
        }

    }

    if ($request->filled('redirect')) {
        return redirect($request->query('redirect'));
    }

    return redirect()->back();
})->name('lang.switch');