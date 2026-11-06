<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    public function show($slug)
    {
      $product = Product::where('id', $slug)->firstOrFail();

        return view('layouts.Customer_view.productview', compact('product'));
    }
}