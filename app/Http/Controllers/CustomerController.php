<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::all();
        return view('customers.index', [
            'customers' => $customers
        ]);
    }
    public function create(): View
    {
        return view('customers.create');
    }  
    
    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'customer'=> $customer
            ]);
    }
    public function store(Request $request,): RedirectResponse
    {
        $validated = $request->validate([
        'name' => 'required|string|max:12',
        'email' => 'required|email|unique:customers,email',
        'company' => 'nullable|string|max:50',
    ]);

    Customer::create($validated);

    return redirect()->route('customers.index');
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
       $validated = $request->validate([
        'name' => 'required|string|max:12',
        'email' => [
            'required',
            'email',
            Rule::unique('customers', 'email')->ignore($customer->id),
        ],
        'company' => 'nullable|string|max:50',
    ]);

    $customer->update($validated);

    return redirect()->route('customers.index');
} 

}
