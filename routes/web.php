<?php

use Illuminate\Support\Facades\Route;

Route::get('/Dashboard', function () {
    return view('Dashboard', [
        'pageTitle' => 'Dashboard',
        'OnlineCount' => 24,
    ]);
});


Route::view('/Dashboard', 'Dashboard')->name('Dashboard');
Route::view('/Customers', 'Customers')->name('Customers');
Route::view('/Orders', 'Orders')->name('Orders');