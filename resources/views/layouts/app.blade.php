<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <nav>
        <a href="{{ route('Dashboard') }}">Dashboard</a>
        <a href="{{ route('Customers') }}">Customers</a>
        <a href="{{ route('Orders') }}">Orders</a>
    </nav>
    <main>
        @yield('content')
    </main>
</body>

</html>