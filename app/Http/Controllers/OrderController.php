<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $statuses = [
            'New Order',
            'Order In Production',
            'Order Dispatched',
            'Order Cancelled',
        ];

        $validated = $request->validate([
            'status' => ['nullable', 'string', Rule::in($statuses)],
        ]);

        $selectedStatus = $validated['status'] ?? null;

        $orders = Order::query()
            ->with('customer')
            ->when($selectedStatus, function (Builder $query, string $status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'selectedStatus' => $selectedStatus,
        ]);
    }
}
