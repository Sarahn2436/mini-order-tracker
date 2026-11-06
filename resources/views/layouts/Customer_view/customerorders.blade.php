@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer orders')

@section('content')
    @php
        $orders = [];
    @endphp

    <main class="w-full lg:max-w-4xl max-w-[335px] mx-auto mt-6 px-4">
        <h1 class="text-xl font-bold mb-4">Your Orders</h1>

        @if(empty($orders))
            <p>No orders currently.</p>
        @else
            <select class="border p-2 rounded">
                @foreach ($orders as $order)
                    <option value="{{ $order['id'] }}">
                        {{ $order['name'] }}
                    </option>
                @endforeach
            </select>
        @endif
    </main>
@endsection