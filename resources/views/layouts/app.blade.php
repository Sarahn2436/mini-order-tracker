<!DOCTYPE html>
<!-- layout for navigation and overall page -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Order Tracker</title>
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <style>
        .nav {
            background-color: #333;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-links {
            display: flex;
        }

        .nav a {
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

        /* Search bar styles */
        .search-container {
            padding: 0 16px;
        }

        .search-container form {
            display: flex;
            align-items: center;
        }

        .search-container input[type=text] {
            padding: 6px 10px;
            font-size: 14px;
            border: none;
            border-radius: 4px 0 0 4px;
            outline: none;
        }

        .search-container button {
            padding: 6px 10px;
            background: #ddd;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
        }

        .search-container button:hover {
            background: #ccc;
        }

        body {
            font-family: "Lato", sans-serif;
        }
    </style>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">
    <!-- Header/navigation -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 mx-auto">
        <nav class="nav">
            <div class="nav-links">
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('customers.index') }}"
                    class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">Customers</a>
                <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders') ? 'active' : '' }}">Orders</a>
            </div>

            <!-- searc bar stuff -->
            <div class="search-container">
                <form action="{{ route('orders') }}" method="GET">
                    <input type="text" placeholder="Search..." name="search" value="{{ request('search') }}">
                    <button type="submit">Go</button>
                </form>
            </div>
        </nav>
    </header>

    <!-- main content -->
    <main class="flex-grow w-full lg:max-w-4xl max-w-[335px] mx-auto px-6">
        @yield('content')
    </main>
</body>

</html>