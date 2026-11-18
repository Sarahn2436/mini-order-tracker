@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <h1>Edit Order</h1>
            <a class="button button-secondary" href="{{ route('orders') }}">Back to Orders</a>
        </div>

        <section class="surface">
            <form class="page-stack surface-body" action="{{ route('orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label for="customer_id">Customer Name</label>
                    <select class="form-control" id="customer_id" name="customer_id" required>
                        <option value="">Select a customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((string) old('customer_id', $order->customer_id) === (string) $customer->id)>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                    @if ($customers->isEmpty())
                        <p class="empty-state">Add a customer before updating this order.</p>
                    @endif
                </div>

                <div class="form-field">
                    <label for="product">Product</label>
                    <input class="form-control" id="product" name="product" type="text"
                        value="{{ old('product', $order->product) }}" required>
                    @error('product')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="quantity">Quantity</label>
                    <input class="form-control" id="quantity" name="quantity" type="number" min="1"
                        value="{{ old('quantity', $order->quantity) }}" required>
                    @error('quantity')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="button button-primary" type="submit" @disabled($customers->isEmpty())>Save Changes</button>
                    <a class="button button-secondary" href="{{ route('orders') }}">Cancel</a>
                </div>
            </form>
        </section>
    </div>
@endsection
