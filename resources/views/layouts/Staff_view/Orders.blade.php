<!-- content for orders page -->
@extends('layouts.Staff_view.app')

@section('title', __("Orders"))

@section('content')
    <style>
        .status-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            line-height: 1.4;
        }

        select.status-pill {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 28px;
            cursor: pointer;
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }

        .status-new {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-production {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-despatched {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
            opacity: 0.8;
        }

        .btn-next-step {
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #374151;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-next-step:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
            color: #111827;
        }

        .bulk-bar {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .bulk-bar.visible {
            display: flex;
        }

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

        <!-- Filter by status -->
        <form action="{{ route('orders') }}" method="GET" style="margin-bottom: 15px;">
            <label for="status">{{ __("Filter by Status:") }}</label>
            <select name="status" id="status" onchange="this.form.submit()" style="padding: 5px 8px; border-radius: 4px;">
                <option value="">{{ __("All Statuses") }}</option>
                <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>{{ __("New") }}</option>
                <option value="In production" {{ request('status') == 'In production' ? 'selected' : '' }}>{{ __("In production") }}</option>
                <option value="Despatched" {{ request('status') == 'Despatched' ? 'selected' : '' }}>{{ __("Despatched") }}</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>{{ __("Cancelled") }}</option>
            </select>
        </form>

        <!-- Actions bar -->
        <form id="bulk-action-form" action="{{ route('orders.bulkUpdateStatus') }}" method="POST" onsubmit="return confirmBulkAction()">
            @csrf
            @method('PATCH')
            <div id="bulk-bar" class="bulk-bar">
                <span><strong id="selected-count">0</strong> {{ __("selected") }}</span>
                <select name="status" id="bulk-status-select" required style="padding: 5px 8px; border-radius: 4px;">
                    <option value="">{{ __("Change status to...") }}</option>
                    <option value="In production">{{ __("In production") }}</option>
                    <option value="Despatched">{{ __("Despatched") }}</option>
                    <option value="Cancelled">{{ __("Cancelled") }}</option>
                </select>
                <button type="submit" style="padding: 5px 12px; background-color: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                    {{ __("Apply to Selected") }}
                </button>
            </div>
        </form>

        <!-- rders table -->
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="width: 35px; text-align: center;">
                        <input type="checkbox" id="select-all" title="{{ __('Select all active orders') }}">
                    </th>
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
                        $pillClass = match($order->status) {
                            'New' => 'status-new',
                            'In production' => 'status-production',
                            'Despatched' => 'status-despatched',
                            'Cancelled' => 'status-cancelled',
                            default => 'status-new',
                        };
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            @if(!$isLocked)
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                            @endif
                        </td>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <a href="{{ route('customers.show', $order->customer_id) }}">{{ $order->customer->name ?? __("N/A") }}</a>
                        </td>
                        <td>{!! preg_replace('/(\(x\d+\))/', '<span style="color: red;">$1</span>', e($order->product)) !!}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->unit_price, 2) }}</td>
                        <td>
                            @if($isLocked)
                                <!-- Static pill without dropdown arrow for locked rows -->
                                <span class="status-pill {{ $pillClass }}">
                                    {{ __($order->status) }}
                                </span>
                            @else
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <!-- Dropdown stuff -->
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                                data-current="{{ $order->status }}"
                                                onchange="handleStatusDropdown(this)"
                                                class="status-pill {{ $pillClass }}">
                                            <option value="New"
                                                {{ $order->status == 'New' ? 'selected' : '' }}
                                                {{ $order->status !== 'New' ? 'disabled' : '' }}>
                                                {{ __("New") }}
                                            </option>
                                            <option value="In production"
                                                {{ $order->status == 'In production' ? 'selected' : '' }}>
                                                {{ __("In production") }}
                                            </option>
                                            <option value="Despatched"
                                                {{ $order->status == 'Despatched' ? 'selected' : '' }}>
                                                {{ __("Despatched") }}
                                            </option>
                                            <option value="Cancelled">
                                                {{ __("Cancelled") }}
                                            </option>
                                        </select>
                                    </form>

                                    @if($order->status === 'New')
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="In production">
                                            <button type="submit" class="btn-next-step" title="{{ __('Move to In production') }}">
                                                &rarr; {{ __("Produce") }}
                                            </button>
                                        </form>
                                    @elseif($order->status === 'In production')
                                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Mark order {{ $order->order_number }} as Despatched? This locks the order.');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Despatched">
                                            <button type="submit" class="btn-next-step" title="{{ __('Move to Despatched') }}">
                                                &rarr; {{ __("Despatch") }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </td>
                        @php
                            $isOverdue = false;

                            if (!empty($order->due_date) && $order->due_date !== 'N/A' && !in_array($order->status, ['Despatched', 'Cancelled'])) {
                                try {
                                    $dueDate = \Carbon\Carbon::parse($order->due_date);
                                    $isOverdue = $dueDate->isPast() && !$dueDate->isToday();
                                } catch (\Exception $e) {
                                    $isOverdue = false;
                                }
                            }
                        @endphp

                        <td style="{{ $isOverdue ? 'color: #dc2626; font-weight: bold;' : '' }}">
                            {{ $order->due_date ?: __('N/A') }}
                            @if($isOverdue)
                                <span style="font-size: 0.75rem; background-color: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; margin-left: 4px;">
                                    {{ __('Overdue') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">{{ __("No orders found.") }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="custom-pagination" style="margin-top: 20px; margin-bottom: 40px;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>

    <script>
        function handleStatusDropdown(selectElement) {
            const newStatus = selectElement.value;
            const previousStatus = selectElement.getAttribute('data-current');

            if (newStatus === 'Cancelled' || newStatus === 'Despatched') {
                const warning = newStatus === 'Cancelled'
                    ? 'Are you sure you want to CANCEL this order? This action permanently locks the row.'
                    : 'Are you sure you want to mark this order as DESPATCHED? This locks the row.';

                if (confirm(warning)) {
                    selectElement.form.submit();
                } else {
                    selectElement.value = previousStatus;
                }
            } else {
                selectElement.form.submit();
            }
        }

        const selectAllCheckbox = document.getElementById('select-all');
        const orderCheckboxes = document.querySelectorAll('.order-checkbox');
        const bulkBar = document.getElementById('bulk-bar');
        const selectedCountSpan = document.getElementById('selected-count');
        const bulkForm = document.getElementById('bulk-action-form');

        function updateBulkBar() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            selectedCountSpan.textContent = checkedBoxes.length;

            if (checkedBoxes.length > 0) {
                bulkBar.classList.add('visible');
            } else {
                bulkBar.classList.remove('visible');
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                orderCheckboxes.forEach(cb => cb.checked = this.checked);
                updateBulkBar();
            });
        }

        orderCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                if (!this.checked && selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
                updateBulkBar();
            });
        });

        function confirmBulkAction() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const targetStatus = document.getElementById('bulk-status-select').value;

            if (checkedBoxes.length === 0 || !targetStatus) {
                return false;
            }

            if (!confirm(`Update ${checkedBoxes.length} selected order(s) to "${targetStatus}"?`)) {
                return false;
            }

            bulkForm.querySelectorAll('input[name="order_ids[]"]').forEach(el => el.remove());
            checkedBoxes.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = cb.value;
                bulkForm.appendChild(input);
            });

            return true;
        }
    </script>
@endsection
