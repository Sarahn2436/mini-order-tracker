@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 20px 0; font-family: sans-serif; font-size: 14px; color: #333;">
    <h1 style="font-size: 24px; color: #111; margin-bottom: 24px;">Customer Details</h1>
    
    <div style="border-top: 2px solid #333; padding-top: 16px;">
        <p style="margin: 0 0 12px 0;"><strong style="color: #111;">Name:</strong> {{ $customer->name }}</p>
        <p style="margin: 0 0 12px 0;"><strong style="color: #111;">Email:</strong> {{ $customer->email }}</p>
        <p style="margin: 0 0 24px 0;"><strong style="color: #111;">Company:</strong> {{ $customer->company ?? '—' }}</p>
    </div>

    <div style="border-top: 1px solid #eee; padding-top: 16px;">
        <a href="{{ route('customers.edit', $customer->id) }}" style="background: #333; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block;">
            Edit Profile
        </a>
        <a href="{{ route('customers.index') }}" style="margin-left: 16px; color: #444; text-decoration: none;">
            Back to List
        </a>
    </div>
</div>
@endsection
