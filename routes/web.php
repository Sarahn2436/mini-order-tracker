<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('Dashboard');
});

Route::get('/dashboard', function () {
    return view('Dashboard');
})->name('dashboard');

Route::resource('customers', CustomerController::class);

Route::get('/orders', function () {
    return view('Orders');
})->name('orders');

?>