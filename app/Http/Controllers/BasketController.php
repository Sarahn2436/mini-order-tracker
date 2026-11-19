<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BasketController extends Controller
{
   public function add(Request $request, $id)
{
    $product = Product::findOrFail($id);
    $basket = session()->get('basket', []);

    if (isset($basket[$id])) {
        $basket[$id]['quantity']++;
    } else {
        $basket[$id] = [
            "id"       => $product->id,
            "name"     => $product->name,
            "price"    => $product->price,
            "quantity" => 1,
        ];
    }

    session()->put('basket', $basket);

    return redirect()->route('customer.basket')->with('success', 'Product added to basket!');
}

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

