<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::view('/Dashboard', 'Dashboard') ->name('Dashboard');
Route::view('/Customers', 'Customers')->name('Customers');
Route::view('/Orders', 'Orders')->name('Orders');