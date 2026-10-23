<?php
# calculates data to feed to the orders page
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function showOrders(Request $request): View
    {
        $search = $request->query('search');
        // 1. Capture the incoming status parameter from the dropdown selection
        $status = $request->query('status');

        $orders = Order::query()
            ->select('orders.*')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            // Handles the text search bar input parameters
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('orders.order_number', 'LIKE', "%{$search}%")
                        ->orWhere('orders.product', 'LIKE', "%{$search}%")
                        ->orWhere('orders.status', 'LIKE', "%{$search}%")
                        ->orWhere('orders.due_date', 'LIKE', "%{$search}%")
                        ->orWhere('customers.name', 'LIKE', "%{$search}%")
                        ->orWhere('customers.email', 'LIKE', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('orders.status', $status);
            })
            ->orderBy('orders.created_at', 'desc')
            ->paginate(15);

        return view('Orders', compact('orders'));
    }
}

