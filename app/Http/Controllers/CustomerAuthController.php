<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product; 
use App\Models\Order;

class CustomerAuthController extends Controller
{
    public function showCustomerLogin()
    {
        return view('layouts.Customer_view.customerLogin.CustomerLogin');
    }

    public function customerLogin(Request $request)
    {
        $request->validate([
            'Customeremail' => ['required', 'email'],
            'Customerpassword' => ['required'],
        ]);

        $credentials = [
            'email' => $request->input('Customeremail'),
            'password' => $request->input('Customerpassword'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'Customeremail' => 'The provided credentials do not match our records.',
        ])->onlyInput('Customeremail');
    }

    public function login()
{
    return view('layouts.Customer_view.customerLogin.CustomerLogin');

}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/customerLogin');
    }


    public function showCustomerRegister()
    {
        return view('layouts.Customer_view.customerLogin.CustomerRegister');
    }

    public function Customerregister(Request $request)
    {
        $data = $request->validate([
            'Customername' => ['required', 'string', 'max:255'],
            'Customeremail' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'Customerpassword' => ['required', 'string', 'min:8', 'confirmed'],
        ], [], [
            'Customername' => 'name',
            'Customeremail' => 'email',
            'Customerpassword' => 'password'
        ]);

        $user = User::create([
            'name' => $data['Customername'],
            'email' => $data['Customeremail'],
            'password' => bcrypt($data['Customerpassword']),
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }



public function CustomerDashboard(Request $request)
{
    $query = Product::query();

    // filtering logic
    if ($request->has('search') && $request->filled('search')) {
        $searchTerm = $request->input('search');
        $query->where('name', 'LIKE', "%{$searchTerm}%");
    }

    // fetch either filtered or all products
    $products = $query->get();

    // return the dashboard
    return view('layouts.Customer_view.CustomerDashboard', compact('products'));
}
    public function customerBasket() 
{
    $basket = session()->get('basket', []);

    return view('layouts.Customer_view.CustomerBasket', compact('basket'));
}
}

