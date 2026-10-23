<!-- content for dashboard page -->
@extends('layouts.app')

@section('title', 'Dashboard Summary')

@section('content')
    <div class="container">

        <!-- dashboard summary -->
        <table border="1" cellpadding="15"
            style="width:100%; border-collapse: collapse; margin-bottom: 40px; border-color: black;">
            <thead>
                <tr style="background-color: #f2f2f2; font-size: 0.9em; text-transform: uppercase;">
                    <th style="width: 33.33%;">Orders created today</th>
                    <th style="width: 33.33%;">Total Value of orders</th>
                    <th style="width: 33.33%;">Orders by status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center" style="font-size: 2.2em; font-weight: bold;">
                        {{ $ordersTodayCount }}
                    </td>
                    <td align="center" style="font-size: 2.2em; font-weight: bold;">
                        ${{ number_format($totalValueOpenOrders, 2) }}
                    </td>
                    <td style="padding: 10px 20px;">
                        <table style="width: 100%; font-size: 0.95em;">
                            @forelse($ordersPerStatus as $statusMetric)
                                <tr>
                                    <td style="padding: 3px 0; font-weight: 500;">{{ $statusMetric->status }}:</td>
                                    <td align="right" style="font-weight: bold; color: #333;">{{ $statusMetric->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" style="color: #6c757d; text-align: center;">No metrics available</td>
                                </tr>
                            @endforelse
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- orders due soonest-->
        <h2 style="margin-bottom: 15px;">5 Orders Due Soonest</h2>
        <table border="1" cellpadding="10"
            style="width:100%; border-collapse: collapse; margin-bottom: 40px; border-color: black;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Order Number</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcomingOrders as $upcoming)
                    <tr>
                        <td><strong>{{ $upcoming->order_number }}</strong></td>
                        <td>
                            <a href="{{ route('customers.show', $upcoming->customer_id) }}">
                                {{ $upcoming->customer->name ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $upcoming->product }}</td>
                        <td>{{ $upcoming->quantity }}</td>
                        <td><span style="font-size: 0.9em;">{{ $upcoming->status }}</span>
                        </td>
                        <td style="font-weight: bold;">
                            {{ $upcoming->due_date }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6c757d;">No pending upcoming orders.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

        <canvas id="myChart" style="width:100%; max-width:700px; margin: 30px auto; display: block;"></canvas>

        <script>

            document.addEventListener("DOMContentLoaded", function () {
                new Chart("myChart", {
                    type: "line",
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: "Orders Placed",
                            backgroundColor: "rgba(0, 0, 255, 0.1)",
                            borderColor: "rgba(0, 0, 255, 1.0)",
                            borderWidth: 2,
                            data: {!! json_encode($chartData) !!}
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function (value) { if (value % 1 === 0) { return value; } }
                                }
                            }]
                        }
                    }
                });
            });
        </script>
@endsection