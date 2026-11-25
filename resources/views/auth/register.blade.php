@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <h1>Create Account</h1>
        </div>

        <section class="surface">
            <form class="page-stack surface-body" action="{{ route('register.store') }}" method="POST">
                @csrf

                <div class="form-field">
                    <label for="username">Username</label>
                    <input class="form-control" id="username" name="username" type="text" value="{{ old('username') }}"
                        autocomplete="username" required autofocus>
                    @error('username')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}"
                        autocomplete="username" required>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password"
                        autocomplete="new-password" required>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password"
                        autocomplete="new-password" required>
                </div>

                <div class="form-actions">
                    <button class="button button-primary" type="submit">Create Account</button>
                    <a class="button button-secondary" href="{{ route('login') }}">Back to Log In</a>
                </div>
            </form>
        </section>
    </div>
@endsection
