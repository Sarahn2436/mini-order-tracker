@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer settings')

@section('content')
    <main class="w-full lg:max-w-4xl max-w-[335px] mx-auto mt-6 px-4" style="font-family: sans-serif;">
        <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 24px; color: #111;">Account Settings</h1>

        @if (session('success'))
            <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- update account -->
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 24px;">
            <h2 style="font-size: 18px; font-weight: bold; color: #111; margin-bottom: 8px;">Profile Details</h2>
            <p style="font-size: 14px; color: #4b5563; margin-bottom: 16px;">
                Update your account's name, email address, and company details.
            </p>

            <form action="{{ route('customer.account.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 16px;">
                    <label for="name" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                        Name
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        style="width: 100%; max-width: 400px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    @error('name')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="email" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        style="width: 100%; max-width: 400px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    @error('email')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="company" style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                        Company
                    </label>
                    <input type="text" id="company" name="company" value="{{ old('company', $customer->company ?? '') }}"
                        style="width: 100%; max-width: 400px; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    @error('company')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                    style="background-color: #2563eb; color: white; font-weight: 600; font-size: 14px; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer;">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Delete account -->
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

