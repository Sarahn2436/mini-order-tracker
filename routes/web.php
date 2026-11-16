<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/Orders', [OrderController::class, 'index'])->name('orders');

Route::resource('customers', CustomerController::class)
    ->except(['show']);
