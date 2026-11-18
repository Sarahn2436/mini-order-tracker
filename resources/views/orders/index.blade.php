@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <h1>Orders</h1>
            <div class="form-actions">
                <a class="button button-secondary" href="{{ route('orders.export', request()->only('status')) }}">Export CSV</a>
                <a class="button button-primary" href="{{ route('orders.create') }}">Add Order</a>
            </div>
        </div>

        @if (session()->has('success'))
            <p class="notice-success" role="status">{{ session('success') }}</p>
        @endif

        <section class="surface" aria-label="Order list">
            <div class="surface-body page-stack">
                <form class="inline-form" action="{{ route('orders') }}" method="GET">
                    <div class="form-field">
                        <label for="status">Filter by status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="button button-primary" type="submit">Filter</button>
                    @if ($selectedStatus)
                        <a class="button button-secondary" href="{{ route('orders') }}">Clear</a>
                    @endif
                </form>

                @if ($orders->isNotEmpty())
                    <p class="empty-state">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders</p>
                @endif
            </div>

            @if ($orders->isEmpty())
                <p class="surface-body empty-state">
                    @if ($selectedStatus)
                        No orders found with status {{ $selectedStatus }}.
                    @else
                        No orders have been added yet.
                    @endif
                </p>
            @else
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer?->name ?? 'Unknown customer' }}</td>
                                    <td>{{ $order->product }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ number_format((float) $order->unit_price, 2) }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td style="{{ $order->status !== 'Order Cancelled' && $order->due_date?->isBefore(today()) ? 'color: #b91c1c;' : '' }}">
                                        {{ $order->status === 'Order Cancelled' ? 'N/A' : ($order->due_date?->format('M j, Y') ?? 'N/A') }}
                                    </td>
                                    <td>{{ $order->created_at?->format('M j, Y') }}</td>
                                    <td>
                                        <div class="form-actions">
                                            <a class="button button-secondary" href="{{ route('orders.edit', $order) }}">Edit</a>
                                            <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="button button-danger" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        @if ($orders->hasPages())
            <nav class="pagination" aria-label="Order pages">
                @if ($orders->previousPageUrl())
                    <a href="{{ $orders->previousPageUrl() }}">Previous</a>
                @endif
                <span>Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>
                @if ($orders->nextPageUrl())
                    <a href="{{ $orders->nextPageUrl() }}">Next</a>
                @endif
            </nav>
        @endif
    </div>
@endsection
