@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <h1>Orders</h1>

    <form action="{{ route('orders') }}" method="GET">
        <label for="status">Filter by status</label>
        <select id="status" name="status">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <button type="submit">Filter</button>
        @if ($selectedStatus)
            <a href="{{ route('orders') }}">Clear filter</a>
        @endif
    </form>

    @if ($orders->isNotEmpty())
        <p>Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders</p>
    @endif

    @if ($orders->isEmpty())
        @if ($selectedStatus)
            <p>No orders found with status {{ $selectedStatus }}.</p>
        @else
            <p>No orders have been added yet.</p>
        @endif
    @else
        <table>
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
                        <td>{{ $order->status === 'Order Cancelled' ? 'N/A' : ($order->due_date?->format('M j, Y') ?? 'N/A') }}</td>
                        <td>{{ $order->created_at?->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{ $orders->links() }}
@endsection
