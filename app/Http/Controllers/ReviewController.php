<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
{
    // 1. Authorize that the user purchased the product
    if (!$request->user()->hasPurchasedProduct($productId)) {
        return back()->with('error', 'You can only review products you have purchased.');
    }

    // 2. Validate input
    $request->validate([
        'rating'  => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
    ]);

    // 3. Create review
    Review::create([
        'product_id' => $productId,
        'user_id'    => Auth::id(),
        'rating'     => $request->rating,
        'comment'    => $request->comment,
    ]);

    return back()->with('success', 'Thank you for your review!');
}

}

