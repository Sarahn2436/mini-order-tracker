<?php
# calculates data to feed to the orders page
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function showOrders(Request $request): View
    {
        $search = $request->query('search');
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

        return view('layouts.staff_view.Orders', compact('orders'));
    }

    public function index(): View
    {
        $customer = Customer::where('email', Auth::user()->email)->first();

        $orders = $customer
            ? Order::where('customer_id', $customer->id)
                ->where('status', '!=', 'Cancelled')
                ->latest()
                ->get()
            : collect();

        return view('layouts.Customer_view.customerorders', compact('orders'));
    }


public function remove(Order $order)
{
    if ($order->status === 'Despatched') {
        return redirect()->route('customer.orders')->with('error', 'Despatched orders cannot be cancelled.');
    }

    $order->update([
        'status' => 'Cancelled',
    ]);

    return redirect()->route('customer.orders')->with('success', 'Order cancelled.');
}

public function updateStatus(Request $request, Order $order)
{
    $validated = $request->validate([
        'status' => 'required|in:New,In production,Despatched,Cancelled',
    ]);

    $newStatus = $validated['status'];
    $currentStatus = $order->status;

    // cant change  an order thats despatcjed or camcelled
    if (in_array($currentStatus, ['Despatched', 'Cancelled'])) {
        return back()->with('error', __('Cannot change the status of an order that is already :status.', ['status' => $currentStatus]));
    }

    // makes it so the statuses are in order so the next bit cn work
    $progression = [
        'New' => 1,
        'In production' => 2,
        'Despatched' => 3,
    ];

    // cant change status to a status before it
    if ($newStatus !== 'Cancelled' && $progression[$newStatus] < $progression[$currentStatus]) {
        return back()->with('error', __('You cannot move an order status backwards.'));
    }

    $order->update([
        'status' => $newStatus,
    ]);

    return back()->with('success', __('Order status updated successfully.'));
}





}
