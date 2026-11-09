<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('/Dashboard', function () {
    return view('Dashboard', [
        'pageTitle' => 'Dashboard',
        'OnlineCount' => 24,
    ]);
});


Route::view('/Dashboard', 'Dashboard')->name('Dashboard');
Route::view('/Customers', 'Customers')->name('Customers');
Route::view('/Orders', 'Orders')->name('Orders');
Route::get('/customers', [CustomerController::class, 'index']);
Route::resource('customers', CustomerController::class);
    ->except(['show']);