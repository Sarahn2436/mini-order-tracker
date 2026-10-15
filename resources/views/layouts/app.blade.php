<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Order Tracker</title>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">

    <!-- Header/navigation -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 mx-auto">
        <nav>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('customers') }}">Customers</a>
            <a href="{{ route('orders') }}">Orders</a>
        </nav>
    </header>

    <!-- main content -->
    <main class="flex-grow w-full lg:max-w-4xl max-w-[335px] mx-auto px-6">
        @yield('content')
    </main>

</body>
</html>
