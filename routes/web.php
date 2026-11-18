<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/Dashboard')->middleware('auth');

Route::middleware('auth')->group(function (): void {
    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/Orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/Orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::get('/Orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::post('/Orders', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/Orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/Orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/Orders', [OrderController::class, 'index'])->name('orders');

    Route::resource('customers', CustomerController::class)
        ->except(['show']);
});
