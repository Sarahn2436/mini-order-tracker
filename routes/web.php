<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Dashboard');
});

Route::get('/dashboard', function () {
    return view('Dashboard');
})->name('dashboard');

Route::get('/customers', function () {
    return view('Customers');
})->name('customers');

Route::get('/orders', function () {
    return view('Orders');
})->name('orders');
