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

public function dashboard(Request $request)
{
    $query = Product::query();

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    switch ($request->sort) {
        case 'best_sellers':
            $query->withCount('orders')->orderByDesc('orders_count');
            break;

        case 'price_asc':
            $query->orderBy('price', 'asc');
            break;

        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;

        default:
            $query->latest();
            break;
    }

    $products = $query->get();

    return view('layouts.Customer_view.dashboard', compact('products'));
}

}