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
            ? Order::where('customer_id', $customer->id)->latest()->get()
            : collect();

        return view('layouts.Customer_view.customerorders', compact('orders'));
    }


    public function add(Request $request)
    {
        $basket = session()->get('basket', []);

        if (empty($basket)) {
            return redirect()->route('customer.basket')->with('error', 'Your basket is empty.');
        }

        $user = Auth::user();

        $customer = Customer::firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name]
        );

        $productNames = [];
        $totalQuantity = 0;
        $totalPrice = 0;

        foreach ($basket as $item) {
            $qty = $item['quantity'] ?? 1;
            $price = $item['price'] ?? $item['unit_price'] ?? 0;

            $productNames[] = ($item['name'] ?? 'Product') . ' (x' . $qty . ')';
            $totalQuantity += $qty;
            $totalPrice += ($price * $qty);
        }

        Order::create([
            'customer_id'  => $customer->id,
            'order_number' => 'ORD-' . rand(100000, 999999),
            'product'      => implode(', ', $productNames),
            'quantity'     => $totalQuantity,
            'unit_price'   => $totalPrice,
            'status'       => 'New',
            'due_date'     => now()->addDays(7)->format('Y-m-d'),
        ]);

        session()->forget('basket');

        return redirect()->route('customer.orders')->with('success', 'Order placed successfully!');
    }


    public function remove(Order $order)
{
    $order->delete();

    return redirect()->route('customer.orders')->with('success', 'Order removed.');
}

}
