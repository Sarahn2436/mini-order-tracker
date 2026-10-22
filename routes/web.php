<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use App\Models\Order;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('customers', CustomerController::class);

Route::get('/orders', function (Request $request) {
    $query = Order::with('customer');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(20);

    return view('orders', compact('orders'));
})->name('orders');

?>