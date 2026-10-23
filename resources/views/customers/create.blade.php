# lets you create/add a customer from the webpage

@extends('layouts.app')

@section('content')
    <div
        style="max-width: 500px; margin: 30px; font-family: sans-serif; border: solid, #111, 2px; padding: 40px; background: #F0F0F0">
        <h1 style="font-size: 24px; color: #111; margin-bottom: 24px;">Add New Customer</h1>

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" s
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                @error('name') <span
                    style="display: block; margin-top: 4px; font-size: 13px; font-weight: 600; color: #222;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                @error('email') <span
                    style="display: block; margin-top: 4px; font-size: 13px; font-weight: 600; color: #222;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px;">Company
                    (Optional)</label>
                <input type="text" name="company" value="{{ old('company') }}"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                @error('company') <span
                    style="display: block; margin-top: 4px; font-size: 13px; font-weight: 600; color: #222;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                style="background: #333; color: white; padding: 8px 16px; border: none; border-radius: 4px; font-size: 14px; cursor: pointer;">
                Save Customer
            </button>
            <a href="{{ route('customers.index') }}"
                style="margin-left: 16px; color: #444; text-decoration: none; font-size: 14px;">Cancel</a>
        </form>
    </div>
@endsection