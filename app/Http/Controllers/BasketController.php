<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function add(Request $request, $id)
    {
        #finds the product to add
        $product = Product::findOrFail($id);

        # gets the basket array
        $basket = session()->get('basket', []);

        # adds product details to basekt array
        $basket[$id] = [
            "id" => $product->id,
            "name" => $product->name,
            "price" => $product->price
        ];

        // saves updated array in session
        session()->put('basket', $basket);

        // takes customer to basket page
        return redirect()->route('customer.basket')->with('success', 'Product added successfully!');    }

    public function index()
    {
        // gets basket from session to pass into basket view
        $basket = session()->get('basket', []);

        return view('customer_orders', compact('basket'));
    }
    public function remove($id)
{
    $basket = session()->get('basket', []);

    if (isset($basket[$id])) {
        unset($basket[$id]);
        session()->put('basket', $basket);
    }

    return redirect()->back()->with('success', 'Item removed from basket.');
}

}

