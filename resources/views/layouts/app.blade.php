<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Order Tracker</title>
    <style>
        .nav {
            background-color: #333;
            overflow: hidden;
        }

        .nav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .nav a:hover {
            background-color: #ddd;
            color: black;
        }

        .nav a.active {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">

    <!-- Header/navigation -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 mx-auto">
        <nav>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('customers.index') }}">Customers</a>
            <a href="{{ route('orders') }}">Orders</a>
            <nav class="nav">
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('customers.index') }}"
                    class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">Customers</a>
                <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'active' : '' }}">Orders</a>
            </nav>
    </header>

    <!-- main content -->
    <main class="flex-grow w-full lg:max-w-4xl max-w-[335px] mx-auto px-6">
        @yield('content')
    </main>

</body>

</html>