<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderByDesc('is_active')->latest()->get();

        return view('layouts.Staff_view.Coupons', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0.01',
        ]);

        Coupon::create([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'is_active' => true,
        ]);

        return back()->with('success', __('Coupon created successfully.'));
    }

    public function deactivate(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => false,
        ]);

        return back()->with('success', __('Coupon has been deactivated.'));
    }
}
