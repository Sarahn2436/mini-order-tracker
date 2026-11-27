<!-- content for orders page -->
@extends('layouts.Staff_view.app')

@section('title', __("Orders"))

@section('content')
    <div class="container">
        <h1>{{ __("Orders") }}</h1>

       @if(session('success'))
            <div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 15px; border-radius: 4px;">
                {{ session('error') }}
            </div>
        @endif


        <!--filter by status stuff -->
        <form action="{{ route('orders') }}" method="GET" style="margin-bottom: 20px;">
            <label for="status">{{ __("Filter by Status:") }}</label>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="">{{ __("All Statuses") }}</option>
                <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>{{ __("New") }}</option>
                <option value="In production" {{ request('status') == 'In production' ? 'selected' : '' }}>{{ __("In production") }}</option>
                <option value="Despatched" {{ request('status') == 'Despatched' ? 'selected' : '' }}>{{ __("Despatched") }}</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>{{ __("Cancelled") }}</option>
            </select>
        </form>

        <!-- Orders table -->
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>{{ __("Order Number") }}</th>
                    <th>{{ __("Customer") }}</th>
                    <th>{{ __("Product") }}</th>
                    <th>{{ __("Quantity") }}</th>
                    <th>{{ __("Unit Price") }}</th>
                    <th>{{ __("Status") }}</th>
                    <th>{{ __("Due Date") }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $isLocked = in_array($order->status, ['Despatched', 'Cancelled']);
                    @endphp
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <a href="{{ route('customers.show', $order->customer_id) }}">{{ $order->customer->name ?? __("N/A") }}</a>
                        </td>
                        <td>{!! preg_replace('/(\(x\d+\))/', '<span style="color: red;">$1</span>', e($order->product)) !!}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->unit_price, 2) }}</td>
                        <td>
                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <select name="status"
                                        data-current="{{ $order->status }}"
                                        onchange="confirmStatusChange(this)"
                                        {{ $isLocked ? 'disabled' : '' }}
                                        style="padding: 4px 8px; border-radius: 4px;">
                                    <option value="New"
                                        {{ $order->status == 'New' ? 'selected' : '' }}
                                        {{ $order->status !== 'New' ? 'disabled' : '' }}>
                                        {{ __("New") }}
                                    </option>
                                    <option value="In production"
                                        {{ $order->status == 'In production' ? 'selected' : '' }}
                                        {{ $order->status == 'Despatched' ? 'disabled' : '' }}>
                                        {{ __("In production") }}
                                    </option>
                                    <option value="Despatched"
                                        {{ $order->status == 'Despatched' ? 'selected' : '' }}>
                                        {{ __("Despatched") }}
                                    </option>
                                    <option value="Cancelled"
                                        {{ $order->status == 'Cancelled' ? 'selected' : '' }}
                                        {{ $order->status == 'Despatched' ? 'disabled' : '' }}>
                                        {{ __("Cancelled") }}
                                    </option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $order->due_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">{{ __("No orders found.") }}</td>
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
    </div>

    <script>
        function confirmStatusChange(selectElement) {
            const newStatus = selectElement.value;
            const previousStatus = selectElement.getAttribute('data-current');

            if (confirm(`Are you sure you want to change the order status to "${newStatus}"?`)) {
                selectElement.form.submit();
            } else {
                selectElement.value = previousStatus;
            }
        }
    </script>
@endsection