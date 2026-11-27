@extends('layouts.Staff_view.app')

@section('title', __("Coupons"))

@section('content')
    <div class="container">
        <h1>{{ __("Coupons") }}</h1>

        @if(session('success'))
            <div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="padding: 10px; background-color: #f8d7da; color: #721c24; margin-bottom: 15px; border-radius: 4px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add coipon form -->
        <form action="{{ route('coupons.store') }}" method="POST" style="margin-bottom: 25px; padding: 15px; border: 1px solid #ccc; border-radius: 4px;">
            @csrf
            <h3 style="margin-top: 0;">{{ __("Add New Coupon") }}</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                <div>
                    <label for="code">{{ __("Coupon Code") }}</label><br>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="e.g. SAVE20" required style="padding: 6px; text-transform: uppercase;">
                </div>
                <div>
                    <label for="type">{{ __("Discount Type") }}</label><br>
                    <select name="type" id="type" required style="padding: 6px;">
                        <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>{{ __("Percentage (%)") }}</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>{{ __("Fixed Amount (£)") }}</option>
                    </select>
                </div>
                <div>
                    <label for="value">{{ __("Value") }}</label><br>
                    <input type="number" step="0.01" name="value" id="value" value="{{ old('value') }}" placeholder="e.g. 20" required style="padding: 6px;">
                </div>
                <div>
                    <button type="submit" style="padding: 7px 15px; background-color: #333; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                        {{ __("Add Coupon") }}
                    </button>
                </div>
            </div>
        </form>

        <!-- Coupons table -->
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>{{ __("Code") }}</th>
                    <th>{{ __("Type") }}</th>
                    <th>{{ __("Value") }}</th>
                    <th>{{ __("Status") }}</th>
                    <th>{{ __("Actions") }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr style="{{ !$coupon->is_active ? 'background-color: #f9f9f9; color: #888;' : '' }}">
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>
                           @if($coupon->type === 'percent')
                                {{ $coupon->value }}%
                            @else
                                £{{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>
                            @if($coupon->is_active)
                                <span style="color: green; font-weight: bold;">{{ __("Active") }}</span>
                            @else
                                <span style="color: red;">{{ __("Inactive") }}</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->is_active)
                                <form action="{{ route('coupons.deactivate', $coupon->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to deactivate this coupon?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                        {{ __("Deactivate") }}
                                    </button>
                                </form>
                            @else
                                <span>{{ __("Deactivated") }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">{{ __("No coupons found.") }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection