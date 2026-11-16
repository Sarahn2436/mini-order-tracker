@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <div>
                <h1>Customers</h1>
                <p class="empty-state">Manage your customer records.</p>
            </div>
            <a class="button button-primary" href="{{ route('customers.create') }}">Add Customer</a>
        </div>

        @if (session()->has('success'))
            <p class="notice-success" role="status">{{ session('success') }}</p>
        @endif

        <section class="surface" aria-label="Customer list">
            <div class="surface-body">
                <form class="search-form" action="{{ route('customers.index') }}" method="GET">
                    <div class="form-field">
                        <label for="search">Search by name or company</label>
                        <input class="form-control" id="search" name="search" type="search" value="{{ $search }}"
                            placeholder="Enter a name or company">
                    </div>
                    <button class="button button-primary" type="submit">Search</button>
                    @if ($search !== '')
                        <a class="button button-secondary" href="{{ route('customers.index') }}">Clear</a>
                    @endif
                </form>
            </div>

            @if ($customers->isEmpty())
                <p class="surface-body empty-state">
                    @if ($search !== '')
                        No customers match {{ $search }}.
                    @else
                        No customers have been added yet.
                    @endif
                </p>
            @else
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Created</th>
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
                                    <td>{{ $customer->created_at?->format('M j, Y') }}</td>
                                    <td>
                                        <div class="form-actions">
                                            <a class="button button-secondary" href="{{ route('customers.edit', $customer) }}">Edit</a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="button button-danger" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
