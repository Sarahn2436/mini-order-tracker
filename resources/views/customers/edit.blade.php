@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
    <h1>Edit Customer</h1>

    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')

        <fieldset>
            <legend>Customer details</legend>

            <label for="name">Name</label><br>
            <input id="name" type="text" name="name" value="{{ old('name', $customer->name) }}">
            @error('name')
                <p>{{ $message }}</p>
            @enderror
            <br><br>

            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" value="{{ old('email', $customer->email) }}">
            @error('email')
                <p>{{ $message }}</p>
            @enderror
            <br><br>

            <label for="company">Company</label><br>
            <input id="company" type="text" name="company" value="{{ old('company', $customer->company) }}">
            @error('company')
                <p>{{ $message }}</p>
            @enderror
            <br><br>

            <button type="submit">Update Customer</button>
        </fieldset>
    </form>
@endsection
