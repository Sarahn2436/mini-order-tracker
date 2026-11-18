<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mini Order Tracker')</title>
    <style>
        body {
            margin: 0;
            color: #111827;
            background: #f9fafb;
            font-family: Arial, sans-serif;
        }

        .app-header,
        .app-main {
            width: min(100% - 2rem, 64rem);
            margin-inline: auto;
        }

        .app-header {
            padding-top: 1.5rem;
        }

        .app-main {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            padding-block: 1.5rem 3rem;
        }

        .nav {
            background-color: #333;
            display: flex;
            border-radius: 0.5rem;
            flex-wrap: wrap;
            overflow: hidden;
        }

        .nav a {
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .nav form {
            display: flex;
            margin-left: auto;
        }

        .nav button {
            border: 0;
            background: transparent;
            color: #f2f2f2;
            padding: 14px 16px;
            font: inherit;
            font-size: 17px;
            cursor: pointer;
        }

        .nav a:hover,
        .nav button:hover {
            background-color: #ddd;
            color: black;
        }

        .nav a.active {
            background-color: #ddd;
            color: black;
        }

        h1,
        h2,
        h3,
        p {
            margin-top: 0;
        }

        h1 {
            margin-bottom: 0;
            font-size: 1.875rem;
        }

        h2 {
            margin-bottom: 0;
            font-size: 1.25rem;
        }

        .page-stack,
        .page-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .page-header,
        .toolbar,
        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .surface {
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: #fff;
            box-shadow: 0 1px 2px rgb(15 23 42 / 6%);
        }

        .surface-body {
            padding: 1.25rem;
        }

        .search-form,
        .inline-form {
            display: flex;
            align-items: end;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .form-field {
            display: flex;
            flex: 1 1 15rem;
            flex-direction: column;
            gap: 0.375rem;
        }

        .form-field label,
        .field-label {
            color: #374151;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            box-sizing: border-box;
            min-height: 2.625rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: #fff;
            color: #111827;
            font: inherit;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: 3px solid rgb(37 99 235 / 20%);
        }

        .button {
            display: inline-flex;
            min-height: 2.625rem;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 0.875rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            font: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .button-primary {
            background: #1d4ed8;
            color: #fff;
        }

        .button-secondary {
            border-color: #d1d5db;
            background: #fff;
            color: #1f2937;
        }

        .button-danger {
            border-color: #fecaca;
            background: #fff;
            color: #b91c1c;
        }

        .button:hover {
            filter: brightness(0.96);
        }

        .button:focus-visible,
        .nav a:focus-visible,
        .nav button:focus-visible,
        .pagination a:focus-visible {
            outline: 3px solid #60a5fa;
            outline-offset: 2px;
        }

        .table-scroll {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .data-table th {
            background: #f3f4f6;
            color: #374151;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .data-table tbody tr:hover {
            background: #f9fafb;
        }

        .notice-success {
            padding: 0.75rem 1rem;
            border: 1px solid #86efac;
            border-radius: 0.5rem;
            background: #dcfce7;
            color: #166534;
        }

        .field-error,
        .empty-state {
            color: #6b7280;
        }

        .field-error {
            margin: 0;
            color: #b91c1c;
            font-size: 0.875rem;
        }

        .empty-state {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .pagination a {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background: #fff;
            color: #1f2937;
            text-decoration: none;
        }

        @media (prefers-color-scheme: dark) {
            body {
                color: #f9fafb;
                background: #111827;
            }

            .surface,
            .form-control,
            .button-secondary {
                border-color: #374151;
                background: #1f2937;
                color: #f9fafb;
            }

            .form-field label,
            .field-label,
            .data-table th {
                color: #e5e7eb;
            }

            .data-table th,
            .data-table td {
                border-color: #374151;
            }

            .data-table th {
                background: #1f2937;
            }

            .data-table tbody tr {
                background: #111827;
            }

            .data-table tbody tr:hover {
                background: #1f2937;
            }

            .pagination a {
                border-color: #374151;
                background: #1f2937;
                color: #f9fafb;
            }

            .notice-success {
                border-color: #166534;
                background: #052e16;
                color: #bbf7d0;
            }

            .field-error,
            .empty-state {
                color: #d1d5db;
            }
        }

        @media (max-width: 640px) {
            .nav a {
                flex: 1 1 auto;
            }

            .page-header {
                align-items: flex-start;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <header class="app-header">
        <nav class="nav" aria-label="Main navigation">
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">Customers</a>
                <a href="{{ route('orders') }}" class="{{ request()->routeIs('orders*') ? 'active' : '' }}">Orders</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}">Mini Order Tracker</a>
            @endauth
        </nav>
    </header>

    <main class="app-main">
        @yield('content')
    </main>

</body>

</html>
