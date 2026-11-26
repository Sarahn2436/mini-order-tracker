<!-- displays detailed profile view for a customer -->
@extends('layouts.Staff_view.app')

@section('content')
    <div style="max-width: 100%; margin: 20px 0; font-family: sans-serif; font-size: 14px; color: #333;">
        <!-- Customer profileinfo stuff -->
        <h1 style="font-size: 24px; color: #111; margin-bottom: 24px;">{{ __('Customer Details') }}</h1>

        <div style="border-top: 2px solid #333; padding-top: 16px;">
            <p style="margin: 0 0 12px 0;"><strong style="color: #111;">{{ __('Name') }}:</strong> {{ $customer->name }}</p>
            <p style="margin: 0 0 12px 0;"><strong style="color: #111;">{{ __('Email') }}:</strong> {{ $customer->email }}</p>
            <p style="margin: 0 0 24px 0;"><strong style="color: #111;">{{ __('Company') }}:</strong> {{ $customer->company ?? '—' }}
            </p>
        </div>

        <div style="border-top: 1px solid #eee; padding-top: 16px; margin-bottom: 30px;">
            <a href="{{ route('customers.edit', $customer->id) }}"
                style="background: #333; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block;">
                {{ __('Edit Profile') }}
            </a>
            <a href="{{ route('customers.index') }}" style="margin-left: 16px; color: #444; text-decoration: none;">
                {{ __('Back to List') }}
            </a>
        </div>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ccc;">

        <!-- connected orders history tabel -->
        <h2 style="font-size: 20px; color: #222; margin-bottom: 15px;">{{ __('Orders History for This Customer') }}</h2>

        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>{{ __('Order Number') }}</th>
                    <th>{{ __('Product') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Unit Price') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Due Date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customer->orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->product }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>£{{ number_format($order->unit_price, 2) }}</td>
                        <td>{{ __($order->status) }}</td>
                        <td>{{ $order->due_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #666; padding: 20px;">
                            {{ __("This customer hasn't placed any orders yet.") }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
