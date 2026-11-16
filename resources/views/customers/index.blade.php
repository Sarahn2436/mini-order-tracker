@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <a href="{{ route('customers.create') }}">Add Customer</a>

    <h1>All Customers</h1>

    @if (session()->has('success'))
        <p role="status" style="padding: 10px; margin: 12px 0; color: #166534; background: #dcfce7; border: 1px solid #86efac;">
            {{ session('success') }}
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->company ?? 'N/A' }}</td>
                    <td>{{ $customer->created_at }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer) }}">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;"
                            onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
