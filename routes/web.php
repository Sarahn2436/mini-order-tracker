<?php

use App\Http\Controllers\CustomerAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;

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

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/customer-dashboard', [CustomerAuthController::class, 'CustomerDashboard'])->name('customer.dashboard');
    Route::post('/customer-logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
});
?>