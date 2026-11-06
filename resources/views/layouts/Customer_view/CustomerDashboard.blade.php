@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer orders')

@section('content')
    <html>

    <head>
        <style>
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
    </style>
    </head>

    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b1b] flex flex-col min-h-screen">

        <div class="container" style="padding: 20px;">
            <!-- The Hero Banner Container -->
            <div class="hero-banner">
                <div class="hero-overlay">
                    <div class="hero-content">
                        <h1> Welcome Back, {{ auth()->user()->name ?? 'Guest' }}!</h1>
                        <p>Example.</p>
                        <a href="#" class="hero-btn">example</a>
                    </div>
                </div>
            </div>

            <!-- shop items grid heading -->
            <p style="font-size: 30px; font-weight: bold; text-align: center; margin: 40px 0 20px 0;">example text</p>

            <!-- Grid Container -->
            <div class="category-grid-container" style="
                display: grid !important;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)) !important;
                gap: 20px !important;
                max-width: 1200px !important;
                margin: 0 auto !important;
                padding: 0 20px !important;
                box-sizing: border-box !important;
            ">
                @if(request('search'))
                    <div style="grid-column: 1 / -1; margin-bottom: 10px; font-size: 18px;">
                        Showing results for: <strong>"{{ request('search') }}"</strong>
                        <a href="{{ route('customer.dashboard') }}"
                            style="margin-left: 15px; color: #007bff; text-decoration: none; font-size: 14px;">Clear Search</a>
                    </div>
                @endif

                @forelse($products as $product)
                    <!-- The Card Box Wrapper -->
                    <div
                        class="grid-item-card bg-[#f8f9fa] border border-[#ddd] rounded-[8px] p-[15px] flex flex-col items-center justify-between text-center shadow-[0_2px_5px rgba(0,0,0,0.05)]">

                        <!-- The Image Box -->
                        <div
                            class="placeholder-canvas-box w-full aspect-square flex items-center justify-center overflow-hidden bg-white rounded-[4px] mb-3">
                            @php
                                $imageFile = match ($product->name) {
                                    'Laptop' => 'laptop.webp',
                                    'Monitor' => 'monitor.webp',
                                    'Desk Chair' => 'chair.webp',
                                    'Keyboard' => 'keyboard.jpg',
                                    'Mouse Pad' => 'mousepad.webp',
                                    default => 'computer.jpeg'
                                };
                            @endphp
                            <img src="{{ asset('images/' . $imageFile) }}" alt="{{ $product->name }}"
                                style="max-width: 100% !important; max-height: 100% !important; object-fit: contain !important; display: block !important;">
                        </div>

                        <!-- Link Text -->
                        <a href="{{ route('customer.product', $product) }}"
                            class="grid-item-label text-[16px] font-semibold text-[#333] no-underline mt-[5px]">
                            {{ $product->name }}
                        </a>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; color: #888; font-size: 18px; padding: 40px 20px;">
                        @if(request('search'))
                            No products found matching "{{ request('search') }}".
                        @else
                            No products found in the database yet!
                        @endif
                    </div>
                @endforelse

            </div>
        </div>

    </body>
@endsection