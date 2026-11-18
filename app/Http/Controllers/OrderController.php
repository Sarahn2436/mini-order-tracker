<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    private const STATUSES = [
        'New Order',
        'Order In Production',
        'Order Dispatched',
        'Order Cancelled',
    ];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', Rule::in(self::STATUSES)],
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
            'statuses' => self::STATUSES,
            'selectedStatus' => $selectedStatus,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', Rule::in(self::STATUSES)],
        ]);

        $selectedStatus = $validated['status'] ?? null;
        $orders = Order::query()
            ->with('customer')
            ->when($selectedStatus, function (Builder $query, string $status): void {
                $query->where('status', $status);
            })
            ->latest()
            ->orderByDesc('id')
            ->lazy(500);

        return response()->streamDownload(function () use ($orders): void {
            $output = fopen('php://output', 'w');

            if ($output === false) {
                throw new \RuntimeException('Unable to open the CSV output stream.');
            }

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'Order Number',
                'Customer',
                'Product',
                'Quantity',
                'Unit Price',
                'Status',
                'Due Date',
                'Created',
            ], ',', '"', '');

            foreach ($orders as $order) {
                fputcsv($output, [
                    $order->order_number,
                    $this->protectCsvFormula($order->customer?->name ?? ''),
                    $this->protectCsvFormula($order->product),
                    $order->quantity,
                    $order->unit_price,
                    $order->status,
                    $order->due_date?->format('Y-m-d') ?? '',
                    $order->created_at?->format('Y-m-d') ?? '',
                ], ',', '"', '');
            }

            fclose($output);
        }, 'orders.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function protectCsvFormula(string $value): string
    {
        if (preg_match('/^[\x00-\x20]*[=+\-@]/', $value) === 1) {
            return "'{$value}";
        }

        return $value;
    }

    public function create(): View
    {
        return view('orders.create', [
            'customers' => Customer::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(Order $order): View
    {
        return view('orders.edit', [
            'customers' => Customer::query()->orderBy('name')->get(),
            'order' => $order,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $statuses = [
            'New Order',
            'Order In Production',
            'Order Dispatched',
            'Order Cancelled',
        ];
        $status = fake()->randomElement($statuses);

        $dueDate = match ($status) {
            'Order Cancelled' => null,
            'Order Dispatched' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            default => fake()->optional()->dateTimeBetween('now', '+30 days')?->format('Y-m-d'),
        };

        do {
            $orderNumber = 'ORD-'.fake()->numerify('######');
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        Order::create([
            ...$validated,
            'order_number' => $orderNumber,
            'unit_price' => fake()->randomFloat(2, 1, 1000),
            'status' => $status,
            'due_date' => $dueDate,
        ]);

        return redirect()->route('orders')->with('success', 'Order added successfully.');
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'product' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order->update($validated);

        return redirect()->route('orders')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders')->with('success', 'Order deleted successfully.');
    }
}
