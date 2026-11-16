@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <h1>Edit Customer</h1>
            <a class="button button-secondary" href="{{ route('customers.index') }}">Back to Customers</a>
        </div>

        <section class="surface">
            <form class="page-stack surface-body" action="{{ route('customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-field">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text"
                        value="{{ old('name', $customer->name) }}" required>
                    @error('name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email"
                        value="{{ old('email', $customer->email) }}" required>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="company">Company <span class="empty-state">(optional)</span></label>
                    <input class="form-control" id="company" name="company" type="text"
                        value="{{ old('company', $customer->company) }}">
                    @error('company')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <button class="button button-primary" type="submit">Save Changes</button>
                    <a class="button button-secondary" href="{{ route('customers.index') }}">Cancel</a>
                </div>
            </form>
        </section>
    </div>
@endsection
