@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: 'Lato', sans-serif; margin-top: 20px;">
        <h1 style="font-size: 28px; color: #111; margin-bottom: 24px; font-weight: 700;">Customers</h1>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('customers.create') }}"
                style="background: #333; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px;">
                + Add New Customer
            </a>
        </div>

        <table border="1" cellpadding="10"
            style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; border-color: black; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2; font-weight: 700; color: #111;">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('customers.show', $customer->id) }}" style="font-weight: 600;">
                                {{ $customer->name }}
                            </a>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->company ?? '—' }}</td>
                        <td style="text-align: center; white-space: nowrap;">
                            <a href="{{ route('customers.show', $customer->id) }}"
                                style="color: #0000ee; text-decoration: underline; margin-right: 10px;">View</a>
                            <a href="{{ route('customers.edit', $customer->id) }}"
                                style="color: #0000ee; text-decoration: underline; margin-right: 10px;">Edit</a>

                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                style="display: inline-block;"
                                onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background: none; border: none; color: #0000ee; padding: 0; cursor: pointer; font-family: inherit; text-decoration: underline; font-size: inherit;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(method_exists($customers, 'links'))
            <div class="custom-pagination" style="margin-top: 20px; margin-bottom: 40px;">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection