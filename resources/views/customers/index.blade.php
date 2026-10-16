@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; font-family: sans-serif;">
        <h1 style="margin: 0; font-size: 24px; color: #111;">Customers</h1>
        <a href="{{ route('customers.create') }}" style="background: #333; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">
            Add Customer
        </a>
    </div>

    @if(session('success'))
        <div style="padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; font-size: 14px; font-family: sans-serif; color: #333;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; text-align: left; border-collapse: collapse; font-family: sans-serif; font-size: 14px;">
        <thead>
            <tr style="border-bottom: 2px solid #333; color: #111;">
                <th style="padding: 10px 8px; font-weight: 600;">Name</th>
                <th style="padding: 10px 8px; font-weight: 600;">Email</th>
                <th style="padding: 10px 8px; font-weight: 600;">Company</th>
                <th style="padding: 10px 8px; font-weight: 600; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody style="color: #444;">
            @foreach($customers as $customer)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 8px;">{{ $customer->name }}</td>
                    <td style="padding: 12px 8px;">{{ $customer->email }}</td>
                    <td style="padding: 12px 8px;">{{ $customer->company ?? '—' }}</td>
                    <td style="padding: 12px 8px; text-align: right;">
                        <a href="{{ route('customers.show', $customer->id) }}" style="color: #111; margin-left: 12px;">View</a>
                        <a href="{{ route('customers.edit', $customer->id) }}" style="color: #111; margin-left: 12px;">Edit</a>
                        
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; padding: 0; color: #111; text-decoration: underline; cursor: pointer; font-size: 14px; margin-left: 12px; font-family: inherit;">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


