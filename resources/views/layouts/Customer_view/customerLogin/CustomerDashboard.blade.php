@section('title', 'Customer Dashboard')

@section('content')
<html>

<head>
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

        .hero-banner {
            width: 100%;
            height: 350px;
            background-image: url('https://unsplash.com');
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .hero-overlay {
            width: 100%;
            height: 100%;
            background: #333;
            display: flex;
            align-items: center;
            padding: 0 40px;
            box-sizing: border-box;
        }

        .hero-content {
            color: white;
            max-width: 500px;
        }

        .hero-badge {
            background-color: #ddd;
            color: #1b1b1b;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            letter-spacing: 1px;
        }

        .hero-content h1 {
            font-size: 32px;
            margin: 12px 0;
            font-weight: bold;
        }

        .hero-content p {
            font-size: 16px;
            color: #f2f2f2;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .hero-btn {
            display: inline-block;
            background-color: white;
            color: #333;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
        }

        .hero-btn:hover {
            background-color: #ddd;
            color: black;
        }


        .category-grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin: 40px auto;
            width: 100%;
            max-width: 1200px;
        }

        .grid-item-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: transparent;
        }

        .placeholder-canvas-box {
            width: 100%;
            aspect-ratio: 3 / 4;
            background-color: #E8EBEF;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #DDE2E7;
            transition: background-color 0.2s ease-in-out;
        }

        .grid-item-card:hover .placeholder-canvas-box {
            background-color: #DFE3E8;
        }

        .placeholder-icon {
            font-size: 14px;
            color: #8C96A3;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grid-item-label {
            margin-top: 14px;
            font-size: 15px;
            color: #333333;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.2px;
            transition: color 0.2s ease;
        }

        .grid-item-label:hover {
            color: #000000;
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">
    <!-- Header/navigation -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 mx-auto">
        <nav class="nav">
            <div class="nav-links">
                <a href="#" class="">example</a>
                <a href="#" class="">example</a>
                <a href="#" class="">example</a>
            </div>

            <!-- search bar -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="search-container" style="padding: 0;">
                    <form action="{{ route('orders') }}" method="GET">
                        <input type="text" placeholder="Search..." name="search" value="{{ request('search') }}">
                        <button type="submit">Go</button>
                    </form>
                </div>

                <!--logout-->
                <form action="{{ route('customer.logout') }}" method="POST" style="margin: 0; padding-right: 16px;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <div class="container" style="padding: 20px;">
        <!-- The Hero Banner Container -->
        <div class="hero-banner">
            <div class="hero-overlay">
                <div class="hero-content">
                    <h1> Welcome Back, {{ auth()->user()->name }}!</h1>
                    <p>Example.</p>
                    <a href="#" class="hero-btn">example</a>
                </div>
            </div>
        </div>
        <!-- shop items row 1-->
        <p style="font-size: 30px; font-weight: bold; text-align: center;">example text</p>
        <div class="category-grid-container">

            <!-- Placeholder Box 1 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <!-- label placeholder inside boz -->
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 2 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 3 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 4 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

        </div>

        <!-- shop items row 2 -->
        <div class="category-grid-container">

            <!-- Placeholder Box 1 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <!-- label placeholder inside boz -->
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 2 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 3 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 4 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

        </div>

        <!-- shop items row 3 -->
        <div class="category-grid-container">

            <!-- Placeholder Box 1 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <!-- label placeholder inside boz -->
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 2 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 3 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

            <!-- Placeholder Box 4 -->
            <div class="grid-item-card">
                <div class="placeholder-canvas-box">
                    <span class="placeholder-icon">example item picture</span>
                </div>
                <a href="#" class="grid-item-label">example link</a>
            </div>

        </div>

    </div>

</body>

</html>