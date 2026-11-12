@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer settings')

@section('content')
    <main class="w-full lg:max-w-4xl max-w-[335px] mx-auto mt-6 px-4" style="font-family: sans-serif;">
        <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 24px; color: #111;">Account Settings</h1>

        <div style="background: white; border: 1px solid #fecaca; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <h2 style="font-size: 18px; font-weight: bold; color: #dc2626; margin-bottom: 8px;">Delete Account</h2>
            <p style="font-size: 14px; color: #4b5563; margin-bottom: 16px;">
                Once your account is deleted, you will no longer be able to log in. Please enter your password to confirm.
            </p>

            @if ($errors->has('password'))
                <div style="color: #dc2626; font-size: 14px; margin-bottom: 12px;">
                    {{ $errors->first('password') }}
                </div>
            @endif

            <form action="{{ route('customer.account.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete your account?');">
                @csrf
                @method('DELETE')

                <div style="margin-bottom: 16px;">
                    <label for="password" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                        Confirm Password
                    </label>
                    <input type="password" id="password" name="password" required
                        style="width: 100%; max-width: 320px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                </div>

                <button type="submit"
                    style="background-color: #dc2626; color: white; font-weight: 600; font-size: 14px; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer;">
                    Delete My Account
                </button>
            </form>
        </div>
    </main>
@endsection
