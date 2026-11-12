@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer orders')

@section('content')
    <main class="w-full lg:max-w-4xl max-w-[335px] mx-auto mt-6 px-4" style="font-family: sans-serif;">
        <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 24px; color: #111;">Your Orders</h1>

        @if($orders->isEmpty())
            <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px; text-align: center; color: #6c757d;">
                <p style="font-size: 16px; margin: 0;">No orders currently.</p>
            </div>
        @else
            <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b;">Order Number</th>
                            <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b; text-align: right;">Order Product</th>
                            <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b; text-align: right;">Order Status</th>
                            <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b; text-align: center;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 16px; font-size: 16px; font-weight: 500; color: #1e293b;">
                                    {{ $order->order_number }}
                                </td>
                                <td style="padding: 16px; font-size: 16px; font-weight: 600; color: #1e293b; text-align: right;">
                                     {!! preg_replace('/(\(x\d+\))/', '<span style="color: #dc2626; font-weight: 700;">$1</span>', e($order->product)) !!}
                                </td>

                                <td style="padding: 16px; font-size: 16px; font-weight: 600; color: #1e293b; text-align: right;">
                                    ({{ $order->status }})
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                @if ($order->status !== 'Despatched' && $order->status !== 'Cancelled')
                                    <form action="{{ route('order.remove', $order) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background: none; border: none; color: #333; font-size: 18px; font-weight: bold; cursor: pointer; padding: 4px 8px; transition: color 0.2s;"
                                            title="Cancel order">
                                            ✕
                                        </button>
                                    </form>
                                @else
                                    <span style="color: #94a3b8; font-size: 14px;">—</span>
                                @endif
                            </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </main>
@endsection
