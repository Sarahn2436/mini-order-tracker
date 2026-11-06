<html>

<head <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'customer app')</title>
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
            margin: 0;
        }

        .search-container input[type=text],
        .search-container button,
        .logout-btn {
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            vertical-align: middle;
            margin: 0;
            line-height: normal;
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

        .logout-btn {
            background: #ddd;
            color: black;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-family: inherit;
            transition: background 0.2s ease;
        }

        .logout-btn:hover {
            background: #ccc;
        }

        body {
            font-family: "Lato", sans-serif;
        }
    </style>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">
    <!-- Header/navigation -->
    <header class="w-full lg:max-w-6xl max-w-full text-sm mb-6 mx-auto">
        <nav class="nav">
            <div class="nav-links">
                <a href="{{ route('customer.orders') }}">customer orders</a>
                <a href="{{ route('customer.dashboard') }}">customer dashboard</a>
                <a href="{{ route('customer.basket') }}" class="">basket</a>
            </div>

            <!-- nav container-->
            <div style="display: flex; align-items: center; gap: 16px; margin-left: auto; padding-right: 16px;">

                <!-- basket -->
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('customer.basket') }}">
                        <img src="{{ asset('images/mini basket icon.png') }}" alt="Basket Icon"
                            style="width:42px; height:42px; filter: brightness(0) invert(1);">
                    </a>
                </div>

                <!-- Search bar -->
                <div class="search-container" style="padding: 0;">
                    <form action="{{ route('customer.dashboard') }}" method="GET">
                        <input type="text" placeholder="Search..." name="search" value="{{ request('search') }}">
                        <button type="submit">Go</button>
                    </form>
                </div>

                <!--logout-->
                <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </header>

    @yield('content')
</body>

</html>