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
        style="display: flex !important; gap: 40px !important; max-width: 1000px !important; margin: 40px auto 10px auto !important; padding: 20px !important; font-family: sans-serif !important; flex-wrap: wrap !important; align-items: flex-start !important;">

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
                    {{ __("Add to Basket") }}
                </button>
            </form>
        </div>
    </div>

    <!-- REVIEWS SECTION CONTAINER -->
    <div class="reviews-section" style="max-width: 1000px !important; margin: 0 auto 40px auto !important; padding: 0 20px !important; font-family: sans-serif !important;">
        <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">
        
        <h2 style="font-size: 24px; margin-bottom: 20px; color: #111;">{{ __("Customer Reviews") }}</h2>

        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 4px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Write a Review Form for logged in users-->
        @auth
            @if(auth()->user()->hasPurchasedProduct($product->id))
                <div style="background: #fdfdfd; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <h3 style="font-size: 18px; margin-top: 0; margin-bottom: 15px;">{{ __("Leave a Review") }}</h3>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px;">{{ __("Rating") }}</label>
                            <select name="rating" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; background: white;">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                <option value="3">⭐⭐⭐ (3/5)</option>
                                <option value="2">⭐⭐ (2/5)</option>
                                <option value="1">⭐ (1/5)</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px;">{{ __("Your Comments") }}</label>
                            <textarea name="comment" required rows="4" placeholder="{{ __("What did you think of this product?") }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: sans-serif; font-size: 14px;"></textarea>
                        </div>

                        <button type="submit" style="background-color: #2c3e50; color: white; border: none; padding: 10px 20px; font-size: 14px; font-weight: bold; border-radius: 4px; cursor: pointer;">
                            {{ __("Submit Review") }}
                        </button>
                    </form>
                </div>
            @else
                <!-- message shown to logged-in users who haven't bought it yet -->
                <div style="background: #f7fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 30px; color: #4a5568; font-style: italic;">
                    🔒 {{ __("Only verified buyers who purchased this product can leave a review.") }}
                </div>
            @endif
        @else
            <p style="color: #666; font-style: italic; margin-bottom: 30px;">{{ __("Please") }} <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none;">{{ __("log in") }}</a> {{ __("to write a review.") }}</p>
        @endauth

        <!-- displaying Exising Reviews List -->
        <div class="reviews-list">
            @forelse($product->reviews as $review)
                <div style="border-bottom: 1px solid #edf2f7; padding: 15px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                        <strong style="font-size: 15px; color: #333;">{{ $review->user->name }}</strong>
                        <span style="color: #f39c12; font-size: 14px;">
                            {{ str_repeat('⭐', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                        </span>
                    </div>
                    <small style="color: #aaa; display: block; margin-bottom: 8px;">{{ __("Reviewed on") }} {{ $review->created_at->format('M d, Y') }}</small>
                    <p style="font-size: 15px; color: #4a5568; margin: 0; line-height: 1.5;">{{ $review->comment }}</p>
                </div>
            @empty
                <p style="color: #888; font-style: italic; padding: 20px 0; text-align: center;">{{ __("No reviews for this product yet. Be the first to leave one!") }}</p>
            @endforelse
        </div>
    </div>
@endsection
