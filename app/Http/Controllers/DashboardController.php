<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statuses = [
            'New Order',
            'Order In Production',
            'Order Dispatched',
            'Order Cancelled',
        ];

        $ordersCreatedToday = Order::query()
            ->whereDate('created_at', today())
            ->count();

        $openOrdersValue = Order::query()
            ->where('status', '!=', 'Order Cancelled')
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total_value')
            ->value('total_value');

        $orderStatusCounts = Order::query()
            ->select('status')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('status')
            ->pluck('order_count', 'status');

        $ordersDueSoon = Order::query()
            ->with('customer:id,name')
            ->where('status', '!=', 'Order Cancelled')
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->orderBy('id')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'ordersCreatedToday' => $ordersCreatedToday,
            'openOrdersValue' => $openOrdersValue,
            'statuses' => $statuses,
            'orderStatusCounts' => $orderStatusCounts,
            'ordersDueSoon' => $ordersDueSoon,
        ]);
    }
}
