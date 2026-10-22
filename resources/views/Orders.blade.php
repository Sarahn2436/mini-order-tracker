@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="container">
        <h1>Orders</h1>

        <!--filter by status stuff -->
        <form action="{{ route('orders') }}" method="GET" style="margin-bottom: 20px;">
            <label for="status">Filter by Status:</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                <option value="In production" {{ request('status') == 'In production' ? 'selected' : '' }}>In production
                </option>
                <option value="Despatched" {{ request('status') == 'Despatched' ? 'selected' : '' }}>Despatched</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>

        <!-- Orders table -->
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Status</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td><a
                                href="{{ route('customers.show', $order->customer_id) }}">{{ $order->customer->name ?? 'N/A' }}</a>
                        </td>
                        <td>{{ $order->product }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->unit_price, 2) }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->due_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- pagination links stuff -->
        <style>
            .custom-pagination ul.pagination {
                list-style-type: none !important;
                padding-left: 0 !important;
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                gap: 8px !important;
                margin-top: 15px;
            }

            .custom-pagination ul.pagination li {
                display: inline-block !important;
            }

            .custom-pagination ul.pagination a,
            .custom-pagination ul.pagination span {
                text-decoration: none !important;
                padding: 6px 12px !important;
                border: 1px solid #ced4da !important;
                border-radius: 4px !important;
                color: #333 !important;
                background-color: #fff !important;
            }

            .custom-pagination ul.pagination li.active span {
                background-color: #333 !important;
                color: white !important;
                border-color: #333 !important;
            }

            .custom-pagination ul.pagination li.disabled span {
                color: #6c757d !important;
                background-color: #f8f9fa !important;
                border-color: #dee2e6 !important;
            }
        </style>


        <div class="custom-pagination" style="margin-top: 20px; margin-bottom: 40px;">
            {{ $orders->appends(request()->query())->links() }}
        </div>

@endsection