<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::view('/Dashboard', 'Dashboard')->name('Dashboard');

Route::view('/Orders', 'Orders')->name('Orders');

Route::resource('customers', CustomerController::class)
    ->except(['show']);
