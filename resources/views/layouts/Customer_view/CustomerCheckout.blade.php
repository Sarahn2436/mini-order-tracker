@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer orders')

@section('content')

<style>
    .checkout-container {
        max-width: 960px;
        margin: 40px auto;
        padding: 25px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }
    .checkout-main {
        flex: 1.5;
        min-width: 0;
    }
    .checkout-sidebar {
        flex: 1;
        position: sticky;
        top: 20px; 
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }
    .form-group input[type="text"],
    #coupon-code {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 15px;
    }
    .form-row {
        display: flex;
        gap: 15px;
    }
    .form-row .form-group {
        flex: 1;
    }
    fieldset {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 20px;
    }
    legend {
        font-weight: bold;
        padding: 0 10px;
        color: #4a5568;
    }

    #card-element {
        background-color: white;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        margin-top: 5px;
        min-height: 40px;
        box-sizing: border-box;
    }

    #apply-coupon-btn {
        background-color: #333;
        color: white;
        padding: 0 16px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
    }
    #apply-coupon-btn:hover {
        background-color: #555;
    }
    .submit-btn {
        width: 100%;
        background-color: #333;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.2s;
    }
    .submit-btn:hover {
        background-color: #555;
    }
    .submit-btn:disabled {
        background-color: #aaa;
        cursor: not-allowed;
    }

    /* for mobile */
    @media (max-width: 768px) {
        .checkout-container {
            flex-direction: column;
            margin: 20px;
        }
        .checkout-main,
        .checkout-sidebar {
            width: 100%;
            position: static;
        }
    }
</style>

<div class="checkout-container">
    <!-- Left Column: Main Checkout Form -->
    <div class="checkout-main">
        <form class="checkout-form" id="paymentForm">
            @csrf
            <fieldset>
                <legend>{{ __('Shipping Information') }}</legend>
                <div class="form-group">
                    <label for="shipping-name">{{ __('Full Name') }}</label>
                    <input type="text" id="shipping-name" name="shipping_name" autocomplete="name" required>
                </div>
                <div class="form-group">
                    <label for="shipping-address">{{ __('Address') }}</label>
                    <input type="text" id="shipping-address" name="shipping_address" autocomplete="street-address" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="shipping-city">{{ __('City') }}</label>
                        <input type="text" id="shipping-city" name="shipping_city" autocomplete="address-level2" required>
                    </div>
                    <div class="form-group">
                        <label for="shipping-zip">{{ __('Zip Code') }}</label>
                        <input type="text" id="shipping-zip" name="shipping_zip" autocomplete="postal-code" required>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>{{ __('Payment Details') }}</legend>
                <div class="form-group">
                    <label for="card-name">{{ __('Name on Card') }}</label>
                    <input type="text" id="card-name" name="card_name" autocomplete="cc-name" required>
                </div>
                <div class="form-group">
                    <label for="card-number">{{ __('Card Number') }}</label>
                    <div id="card-number-element" style="background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px; box-sizing: border-box;"></div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="card-expiry">{{ __('Expiry Date (MM/YY)') }}</label>
                        <div id="card-expiry-element" style="background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px; box-sizing: border-box;"></div>
                    </div>
                    <div class="form-group">
                        <label for="card-cvc">{{ __('CVC') }}</label>
                        <div id="card-cvc-element" style="background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px; box-sizing: border-box;"></div>
                    </div>
                </div>
                <div id="card-errors" role="alert" style="color: red; margin-top: 8px; font-size: 14px;"></div>
            </fieldset>

            <fieldset>
                <legend>{{ __('Billing Address') }}</legend>
                <div class="billing-checkbox" style="margin-bottom: 10px;">
                    <input type="checkbox" id="same-as-shipping" name="same_as_shipping" checked>
                    <label for="same-as-shipping">{{ __('Same as shipping address') }}</label>
                </div>
                <div id="billing-address-fieldset" style="display: none;">
                    <div class="form-group"><label for="billing-name">{{ __('Full Name') }}</label><input type="text" id="billing-name" name="billing_name"></div>
                    <div class="form-group"><label for="billing-address">{{ __('Address') }}</label><input type="text" id="billing-address" name="billing_address"></div>
                    <div class="form-row">
                        <div class="form-group"><label for="billing-city">{{ __('City') }}</label><input type="text" id="billing-city" name="billing_city"></div>
                        <div class="form-group"><label for="billing-zip">{{ __('Zip Code') }}</label><input type="text" id="billing-zip" name="billing_zip"></div>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="submit-btn" id="submitBtn">{{ __('Place Order') }}</button>
        </form>
    </div>

    <!-- Right Column: Discount & Total Sidebar -->
    <aside class="checkout-sidebar">
        <fieldset>
            <legend>{{ __('Discount Code') }}</legend>
            <div class="form-row" style="display: flex; gap: 10px;">
                <input type="text" id="coupon-code" name="coupon_code" placeholder="{{ __('Enter coupon code') }}" style="flex: 1;">
                <button type="button" id="apply-coupon-btn">{{ __('Apply') }}</button>
            </div>
            <div id="coupon-message" style="margin-top: 6px; font-size: 14px;"></div>
            <p style="margin-top: 10px;">
                <strong>{{ __('Total:') }} $<span id="display-total">{{ number_format($totalPrice, 2) }}</span></strong>
            </p>
        </fieldset>
    </aside>
</div>


<script src="https://js.stripe.com/v3/"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Stripe === 'undefined') {
        document.getElementById('card-errors').textContent = "{{ __("Payment fields initializing... Please refresh if boxes are blank.") }}";
        return;
    }

    let appliedCoupon = '';

    // Validate coupon via AJAX so the user sees the new price immediately
    document.getElementById('apply-coupon-btn').addEventListener('click', function() {
        const code = document.getElementById('coupon-code').value.trim();
        const messageEl = document.getElementById('coupon-message');
        const csrfToken = document.querySelector('input[name="_token"]').value;

        if (!code) return;

        fetch("{{ route('coupon.apply') }}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                appliedCoupon = code;
                messageEl.style.color = 'green';
                messageEl.textContent = data.message;
                document.getElementById('display-total').textContent = data.new_total;
            } else {
                appliedCoupon = '';
                messageEl.style.color = 'red';
                messageEl.textContent = data.message;
            }
        });
    });

    const stripe = Stripe("{{ env('STRIPE_KEY') }}"); 
    const elements = stripe.elements();

    const elementStyles = {
        base: {
            fontSize: '16px',
            color: '#333333',
            fontFamily: '"Lato", sans-serif',
            '::placeholder': { color: '#aab7c4' }
        }
    };

    const cardNumber = elements.create('cardNumber', { style: elementStyles });
    cardNumber.mount('#card-number-element');

    const cardExpiry = elements.create('cardExpiry', { style: elementStyles });
    cardExpiry.mount('#card-expiry-element');

    const cardCvc = elements.create('cardCvc', { style: elementStyles });
    cardCvc.mount('#card-cvc-element');

    const form = document.getElementById('paymentForm');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        document.getElementById('submitBtn').disabled = true;

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardNumber,
            billing_details: {
                name: document.getElementById('card-name').value,
                address: {
                    city: document.getElementById('shipping-city').value,
                    line1: document.getElementById('shipping-address').value,
                    postal_code: document.getElementById('shipping-zip').value
                }
            }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            document.getElementById('submitBtn').disabled = false;
        } else {
            stripePaymentHandler(paymentMethod);
        }
    });

    function stripePaymentHandler(paymentMethod) {
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch("{{ route('stripe.checkout') }}", {
            method: 'POST',
           headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({
                payment_method_id: paymentMethod.id,
                shipping_name: document.getElementById('shipping-name').value,
                shipping_address: document.getElementById('shipping-address').value,
                shipping_city: document.getElementById('shipping-city').value,
                shipping_zip: document.getElementById('shipping-zip').value,
                coupon_code: appliedCoupon
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.href = "{{ route('customer.orders') }}";
            } else {
                document.getElementById('card-errors').textContent = data.error;
                document.getElementById('submitBtn').disabled = false;
            }
        })
        .catch(err => {
            document.getElementById('card-errors').textContent = "{{ __("Server communication error. Please try again.") }}";
            document.getElementById('submitBtn').disabled = false;
        });
    }
});

const sameAsShippingCheckbox = document.getElementById('same-as-shipping');
const billingAddressFields = document.getElementById('billing-address-fieldset');
if (sameAsShippingCheckbox && billingAddressFields) {
    const toggleBillingAddress = () => {
        billingAddressFields.style.display = sameAsShippingCheckbox.checked ? 'none' : 'block';
    };
    sameAsShippingCheckbox.addEventListener('change', toggleBillingAddress);
    toggleBillingAddress();
}
</script>
@endsection
