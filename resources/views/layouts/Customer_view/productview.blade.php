@extends('layouts.Customer_view.customerapp')

@section('content')
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

    <div class="product-container"
        style="display: flex !important; gap: 40px !important; max-width: 1000px !important; margin: 40px auto !important; padding: 20px !important; font-family: sans-serif !important; flex-wrap: wrap !important; align-items: flex-start !important;">

        <div class="product-image-frame" style="
                flex: 0 0 350px !important; 
                width: 350px !important; 
                height: 350px !important; 
                background-image: url('{{ asset('images/' . $imageFile) }}') !important;
                background-size: contain !important;
                background-position: center !important;
                background-repeat: no-repeat !important;
                background-color: #f8f9fa !important;
                border: 1px solid #ddd !important;
                border-radius: 8px !important;
                padding: 15px !important;
            ">
        </div>

        <!-- Text details stuff -->
        <div class="product-details"
            style="flex: 1 !important; min-width: 300px !important; display: flex !important; flex-direction: column !important; justify-content: center !important;">
            <h1 style="font-size: 32px !important; margin: 0 0 10px 0 !important; color: #111 !important;">
                {{ $product->name }}
            </h1>
            <p class="price"
                style="font-size: 24px !important; font-weight: bold !important; color: #2c3e50 !important; margin: 10px 0 !important;">
                ${{ number_format($product->price, 2) }}
            </p>
            <p class="description"
                style="font-size: 16px !important; color: #555 !important; line-height: 1.6 !important; margin: 20px 0 30px 0 !important;">
                {{ $product->description }}
            </p>

            <form action="{{ route('basket.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit"
                    style="background-color: #333 !important; color: white !important; border: none !important; padding: 12px 24px !important; font-size: 16px !important; font-weight: bold !important; border-radius: 6px !important; cursor: pointer !important; max-width: 200px !important;">
                    Add to Basket
                </button>
            </form>
        </div>
    </div>
@endsection