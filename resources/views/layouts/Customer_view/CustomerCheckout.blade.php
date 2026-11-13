@extends('layouts.Customer_view.customerapp')

@section('title', 'Customer orders')

@section('content')
<style>
    .checkout-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
    .form-group input[type="text"] {
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
</style>

<div class="checkout-container">
    <form class="checkout-form" id="paymentForm">
        @csrf

        <fieldset>
            <legend>Shipping Information</legend>
            <div class="form-group">
                <label for="shipping-name">Full Name</label>
                <input type="text" id="shipping-name" name="shipping_name" autocomplete="name" required>
            </div>
            <div class="form-group">
                <label for="shipping-address">Address</label>
                <input type="text" id="shipping-address" name="shipping_address" autocomplete="street-address" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="shipping-city">City</label>
                    <input type="text" id="shipping-city" name="shipping_city" autocomplete="address-level2" required>
                </div>
                <div class="form-group">
                    <label for="shipping-zip">Zip Code</label>
                    <input type="text" id="shipping-zip" name="shipping_zip" autocomplete="postal-code" required>
                </div>
            </div>
        </fieldset>

        <<fieldset>
    <legend>Payment Details</legend>
    <div class="form-group">
        <label for="card-name">Name on Card</label>
        <input type="text" id="card-name" name="card_name" autocomplete="cc-name" required>
    </div>

    <div class="form-group">
        <label for="card-number">Card Number</label>
        <div id="card-number-element" style="background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px; box-sizing: border-box;"></div>
    </div>
    
    <div class="form-row">

        <div class="form-group">
            <label for="card-expiry">Expiry Date (MM/YY)</label>
            <div id="card-expiry-element" style="background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px; box-sizing: border-box;"></div>
        </div>

        <div class="form-group">
            <label for="card-cvc">CVC</label>
            <div id="card-cvc-element" style="background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 40px; box-sizing: border-box;"></div>
        </div>
    </div>
    <div id="card-errors" role="alert" style="color: red; margin-top: 8px; font-size: 14px;"></div>
</fieldset>
        <fieldset>
            <legend>Billing Address</legend>
            <div class="billing-checkbox" style="margin-bottom: 10px;">
                <input type="checkbox" id="same-as-shipping" name="same_as_shipping" checked>
                <label for="same-as-shipping">Same as shipping address</label>
            </div>
            <div id="billing-address-fieldset" style="display: none;">
                <div class="form-group"><label for="billing-name">Full Name</label><input type="text" id="billing-name" name="billing_name"></div>
                <div class="form-group"><label for="billing-address">Address</label><input type="text" id="billing-address" name="billing_address"></div>
                <div class="form-row">
                    <div class="form-group"><label for="billing-city">City</label><input type="text" id="billing-city" name="billing_city"></div>
                    <div class="form-group"><label for="billing-zip">Zip Code</label><input type="text" id="billing-zip" name="billing_zip"></div>
                </div>
            </div>
        </fieldset>

        <button type="submit" class="submit-btn" id="submitBtn">Place Order</button>
    </form>
</div>

<script src="https://stripe.com"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Stripe === 'undefined') {
        document.getElementById('card-errors').textContent = "Payment fields initializing... Please refresh if boxes are blank.";
        return;
    }

    const stripe = Stripe('pk_test_51UbFrzJWQyE8e8xEzcss17tDfCwwmp2J8ID9PrRYt9LGDzlXkhz0EIyO2njfaqGWZdRXmOuFLP8OJiRT7GxJCqcQ00YaxlvdMK'); 
    const elements = stripe.elements();

    const elementStyles = {
        base: {
            fontSize: '16px',
            color: '#333333',
            fontFamily: '"Lato", sans-serif',
            '::placeholder': { color: '#aab7c4' }
        }
    };

    // card stuff
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
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({
                // Match the variable names expected by your modern controller backend
                payment_method_id: paymentMethod.id,
                shipping_name: document.getElementById('shipping-name').value,
                shipping_address: document.getElementById('shipping-address').value,
                shipping_city: document.getElementById('shipping-city').value,
                shipping_zip: document.getElementById('shipping-zip').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                window.location.href = "/order-success";
            } else {
                document.getElementById('card-errors').textContent = data.error;
                document.getElementById('submitBtn').disabled = false;
            }
        })
        .catch(err => {
            document.getElementById('card-errors').textContent = "Server communication error. Please try again.";
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