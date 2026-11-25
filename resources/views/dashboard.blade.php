@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        .dashboard-summary,
        .dashboard-status-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
        }

        .dashboard-metric {
            margin: 0.5rem 0 0;
            font-size: 1.875rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <div>
                <h1>Welcome Back, {{ auth()->user()->username === auth()->user()->email ? auth()->user()->name : auth()->user()->username }}</h1>
                <p class="empty-state">A summary of your current orders.</p>
            </div>
        </div>

        <section class="dashboard-summary" aria-label="Order summary">
            <article class="surface surface-body">
                <h2 class="field-label">Orders created today</h2>
                <p class="dashboard-metric">{{ $ordersCreatedToday }}</p>
            </article>

            <article class="surface surface-body">
                <h2 class="field-label">Total value of open orders</h2>
                <p class="dashboard-metric">{{ number_format((float) $openOrdersValue, 2) }}</p>
                <p class="empty-state">Quantity multiplied by unit price</p>
            </article>
        </section>

        <section class="page-section" aria-labelledby="order-status-heading">
            <h2 id="order-status-heading">Orders by status</h2>
            <div class="dashboard-status-grid">
                @foreach ($statuses as $status)
                    <article class="surface surface-body">
                        <h3 class="field-label">{{ $status }}</h3>
                        <p class="dashboard-metric">{{ $orderStatusCounts->get($status, 0) }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="page-section" aria-labelledby="due-soon-heading">
            <h2 id="due-soon-heading">5 orders due soonest</h2>

            @if ($ordersDueSoon->isEmpty())
                <p class="empty-state">No orders have a due date.</p>
            @else
                <div class="surface table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordersDueSoon as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer?->name ?? 'Unknown customer' }}</td>
                                    <td>{{ $order->due_date?->format('M j, Y') }}</td>
                                    <td>{{ $order->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
