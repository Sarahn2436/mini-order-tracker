@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer orders')

@section('content')

    <style>
        .checkout-container {
            max-width: 700px;
            margin: 30px auto;
            font-family: sans-serif;
        }

        .checkout-form fieldset {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .checkout-form legend {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 0 10px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
        }

        .form-group:only-child {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        /* Stack columns on mobile */
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 0;
                margin-bottom: 0;
            }

            .form-group {
                margin-bottom: 15px;
            }
        }

        /* -- Billing Section Specific -- */
        #billing-address-fieldset {
            display: block;
        }

        /* Show it by default */
        .billing-checkbox {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* -- Submit Button -- */
        .submit-btn {
            width: 100%;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
        }
    </style>

    <div class="checkout-container">
        <form class="checkout-form" id="checkoutForm" action="#" method="GET">

            <fieldset>
                <legend>Shipping Information</legend>
                <div class="form-group"><label for="shipping-name">Full Name</label><input type="text" id="shipping-name"
                        name="shipping_name" autocomplete="name" required></div>
                <div class="form-group"><label for="shipping-address">Address</label><input type="text"
                        id="shipping-address" name="shipping_address" autocomplete="street-address" required></div>
                <div class="form-row">
                    <div class="form-group"><label for="shipping-city">City</label><input type="text" id="shipping-city"
                            name="shipping_city" autocomplete="address-level2" required></div>
                    <div class="form-group"><label for="shipping-zip">Zip Code</label><input type="text" id="shipping-zip"
                            name="shipping_zip" autocomplete="postal-code" required></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Payment Details</legend>
                <div class="form-group"><label for="card-name">Name on Card</label><input type="text" id="card-name"
                        name="card_name" autocomplete="cc-name" required></div>
                <div class="form-group"><label for="card-number">Card Number</label><input type="text" id="card-number"
                        name="card_number" autocomplete="cc-number" required></div>
                <div class="form-row">
                    <div class="form-group"><label for="card-expiry">Expiry Date (MM/YY)</label><input type="text"
                            id="card-expiry" name="card_expiry" autocomplete="cc-exp" placeholder="MM/YY" required></div>
                    <div class="form-group"><label for="card-cvc">CVC</label><input type="text" id="card-cvc"
                            name="card_cvc" autocomplete="cc-csc" required></div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Billing Address</legend>
                <div class="billing-checkbox">
                    <input type="checkbox" id="same-as-shipping" name="same_as_shipping" checked>
                    <label for="same-as-shipping">Same as shipping address</label>
                </div>
                <!-- can be hidden or shown -->
                <div id="billing-address-fieldset" style="display: none;">
                    <div class="form-group"><label for="billing-name">Full Name</label><input type="text" id="billing-name"
                            name="billing_name" autocomplete="name"></div>
                    <div class="form-group"><label for="billing-address">Address</label><input type="text"
                            id="billing-address" name="billing_address" autocomplete="street-address"></div>
                    <div class="form-row">
                        <div class="form-group"><label for="billing-city">City</label><input type="text" id="billing-city"
                                name="billing_city" autocomplete="address-level2"></div>
                        <div class="form-group"><label for="billing-zip">Zip Code</label><input type="text" id="billing-zip"
                                name="billing_zip" autocomplete="postal-code"></div>
                    </div>
                </div>
            </fieldset>
        </form>
        <form class="checkout-form" id="placeOrderForm" action="{{ route('order.add') }}" method="POST">
            @csrf
            <button type="submit" class="submit-btn">Place Order</button>
        </form>
    </div>

    <script>
        const sameAsShippingCheckbox = document.getElementById('same-as-shipping');
        const billingAddressFields = document.getElementById('billing-address-fieldset');

        if (sameAsShippingCheckbox && billingAddressFields) {
            // toggles billing address
            const toggleBillingAddress = () => {
                if (sameAsShippingCheckbox.checked) {
                    billingAddressFields.style.display = 'none';
                } else {
                    billingAddressFields.style.display = 'block';
                }
            };

            sameAsShippingCheckbox.addEventListener('change', toggleBillingAddress);

            // runs function when the page loads
            toggleBillingAddress();
        }
    </script>
@endsection