<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product; 
use App\Models\Order;
use App\Models\Customer;

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

public function accountSettings()
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $customer = Customer::where('user_id', $user->id)
        ->orWhere('email', $user->email)
        ->first();

    return view('layouts.Customer_view.accountSettings', compact('user', 'customer'));
}

public function updateAccount(Request $request)
{
    // 1. Find the linked user (via user_id or matching current email)
    $user = User::where('id', $customer->user_id)
                ->orWhere('email', $customer->email)
                ->first();

    // 2. Validate against both tables properly
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:255'],
        'email'   => [
            'required',
            'string',
            'email',
            'max:255',
            // Ignore this user's ID in the users table (if a user record exists)
            Rule::unique('users', 'email')->ignore($user?->id),
            // Also ignore this customer's ID in the customers table
            Rule::unique('customers', 'email')->ignore($customer->id),
        ],
        'company' => ['nullable', 'string', 'max:255'],
    ]);

    // 3. Update User if they exist
    if ($user) {
        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);
    }

    // 4. Update Customer
    $customer->update([
        'user_id' => $user?->id,
        'name'    => $validated['name'],
        'email'   => $validated['email'],
        'company' => $validated['company'] ?? null,
    ]);

    return redirect()->route('customer.settings')->with('success', 'Your details have been updated.');
}

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/customerLogin')->with('success', 'Your account has been permanently deleted.');
    }

    public function CustomerDashboard(Request $request)
    {
        $query = Product::query();

        // Text search filtering logic
        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        // Category filtering logic
        if ($request->has('category') && $request->filled('category')) {
            $categoryName = $request->input('category');
            $query->where('category', $categoryName);
        }

        // fetch products
        $products = $query->get();

        // return dashboard
        return view('layouts.Customer_view.CustomerDashboard', compact('products'));
    }

    public function customerBasket()
    {
        $basket = session()->get('basket', []);

        return view('layouts.Customer_view.CustomerBasket', compact('basket'));
    } 

    public function add(Request $request)
    {
        $basket = session()->get('basket', []);

        if (empty($basket)) {
            return redirect()->route('customer.basket')->with('error', 'Your basket is empty.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'status'  => 'pending',
        ]);

        foreach ($basket as $id => $details) {
            \App\Models\OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $id,
                'quantity'   => $details['quantity'] ?? 1,
                'price'      => $details['price'], 
            ]);
        }

        session()->forget('basket');

        return redirect()->route('customer.orders')->with('success', 'Order placed successfully!');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();

        return view('layouts.Customer_view.customerorders', compact('orders'));
    }
}
