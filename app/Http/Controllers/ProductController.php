<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
public function show(Request $request, $id, $slug = null)
{
    $identifier = $slug ?? $id;

    $product = Product::where('id', $identifier)
        ->orWhere('slug', $identifier)
        ->with('reviews.user')
        ->firstOrFail();

    // Return the view with the correctly populated product data model
    return view('layouts.Customer_view.ProductView', compact('product'));
}

}