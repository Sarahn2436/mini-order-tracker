@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h1>Edit Customer: {{ $customer->name }}</h1>

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label style="display:block; font-weight:bold;">Name</label>
            <input type="text" name="name" value="{{ old('name', $customer->name) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            @error('name') <span style="color:red; font-size:14px;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; font-weight:bold;">Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->email) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            @error('email') <span style="color:red; font-size:14px;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; font-weight:bold;">Company (Optional)</label>
            <input type="text" name="company" value="{{ old('company', $customer->company) }}" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            @error('company') <span style="color:red; font-size:14px;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" style="background:#333; color:white; padding:10px 15px; border:none; border-radius:4px; cursor:pointer;">Update Customer</button>
        <a href="{{ route('customers.index') }}" style="margin-left:10px; color:#555;">Cancel</a>
    </form>
</div>
@endsection
