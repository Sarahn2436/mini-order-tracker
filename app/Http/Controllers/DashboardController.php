<?php
# calculates data to feed to the dashboard
namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $ordersTodayCount = Order::whereDate('created_at', Carbon::today())->count();

        $totalValueOpenOrders = Order::whereIn('status', ['New', 'In production'])
            ->selectRaw('SUM(quantity * unit_price) as total_value')
            ->value('total_value') ?? 0;

        $ordersPerStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $upcomingOrders = Order::query()
            ->with('customer')
            ->whereNotIn('status', ['Despatched', 'Cancelled'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        $ordersOverTime = Order::selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $dateKey = now()->subDays($i)->format('Y-m-d');

            $displayLabel = now()->subDays($i)->format('M d');

            $chartLabels[] = $displayLabel;

            $chartData[] = $ordersOverTime[$dateKey] ?? 0;
        }

        return view('dashboard', compact(
            'ordersTodayCount',
            'totalValueOpenOrders',
            'ordersPerStatus',
            'upcomingOrders',
            'chartLabels',
            'chartData'
        ));
    }
}