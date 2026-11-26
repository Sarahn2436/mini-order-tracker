@extends('layouts.Customer_view.customerapp')

@section('title', __("Customer basket"))

@section('content')

    <main class="w-full lg:max-w-4xl max-w-[335px] mx-auto mt-6 px-4" style="font-family: sans-serif;">
    <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 24px; color: #111;">{{ __("Your Basket") }}</h1>

    @if(empty($basket))
        <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px; text-align: center; color: #6c757d;">
            <p style="font-size: 16px; margin: 0;">{{ __("Your basket is currently empty.") }}</p>
        </div>
    @else
        <!-- shopping basket grid -->
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b; width: 100px;">{{ __("Product ID") }}</th>
                        <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b;">{{ __("Item(s)") }}</th>
                        <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b; text-align: center; width: 100px;">{{ __("Qty") }}</th>
                        <th style="padding: 16px; font-size: 14px; font-weight: 600; color: #64748b; text-align: right; width: 150px;">{{ __("Price") }}</th>
                        <th style="padding: 16px; width: 60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPrice = 0; @endphp
                    @foreach ($basket as $id => $order)
                        @php 
                            $itemQuantity = $order['quantity'] ?? 1;
                            $itemSubtotal = $order['price'] * $itemQuantity; 
                            $totalPrice += $itemSubtotal;
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px; font-size: 14px; color: #64748b; font-family: monospace;">
                                #{{ $id }}
                            </td>
                            <td style="padding: 16px; font-size: 16px; font-weight: 500; color: #1e293b;">
                                {{ $order['name'] }}
                            </td>
                            <td style="padding: 16px; font-size: 16px; color: #1e293b; text-align: center;">
                                {{ $itemQuantity }}
                            </td>
                            <td style="padding: 16px; font-size: 16px; font-weight: 600; color: #1e293b; text-align: right;">
                                ${{ number_format($itemSubtotal, 2) }}
                            </td>
                            <td style="padding: 16px; text-align: center;">
                                <form action="{{ route('basket.remove', $id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #333; font-size: 18px; font-weight: bold; cursor: pointer; padding: 4px 8px; transition: color 0.2s;" title="{{ __("Remove item") }}">
                                        ✕
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- order summary thing -->
            <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="font-size: 18px; font-weight: bold; color: #1e293b;">
                    {{ __("Total:") }} <span style="color: #2c3e50; font-size: 22px; margin-left: 8px;">${{ number_format($totalPrice, 2) }}</span>
                </div>
                <form action="{{ route('customer.checkout') }}" method="GET">
                    <button style="background-color: #333; color: white; border: none; padding: 12px 28px; font-size: 16px; font-weight: bold; border-radius: 6px; cursor: pointer; transition: background 0.2s;">
                        {{ __("Proceed to Checkout") }}
                    </button>
                </form>
            </div>
        </div>
    @endif
</main>
@endsection