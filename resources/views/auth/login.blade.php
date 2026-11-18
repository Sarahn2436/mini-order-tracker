@extends('layouts.app')

@section('title', 'Log In')

@section('content')
    <div class="page-stack">
        <div class="page-header">
            <h1>Log In</h1>
        </div>

        <section class="surface">
            <form class="page-stack surface-body" action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="form-field">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}"
                        autocomplete="username" required autofocus>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password"
                        autocomplete="current-password" required>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="field-label" for="remember">
                    <input id="remember" name="remember" type="checkbox" value="1">
                    Remember me
                </label>

                <div class="form-actions">
                    <button class="button button-primary" type="submit">Log In</button>
                    <a class="button button-secondary" href="{{ route('register') }}">Create Account</a>
                </div>
            </form>
        </section>
    </div>
@endsection
