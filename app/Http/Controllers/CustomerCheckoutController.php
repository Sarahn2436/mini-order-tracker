<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use Stripe\StripeClient; 
use App\Models\Order; 

class CustomerCheckoutController extends Controller 
{ 
    public function CustomerCheckout() 
    { 
        $orders = Order::all(); 
 
        return view('layouts.Customer_view.customercheckout', compact('orders')); 
    } 

    public function add(Request $request) 
    { 
        $stripe = new StripeClient(env('STRIPE_SECRET')); 

        try { 
            $paymentIntent = $stripe->paymentIntents->create([ 
                'amount' => 5000,
                'currency' => 'usd', 
                'payment_method' => $request->input('payment_method_id'), 
                'description' => 'Order checkout payment from ' . $request->input('shipping_name'), 
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never'
                ],
                'return_url' => url('/checkout/completed'),
            ]); 

            $order = Order::create([ 
                'customer_name' => $request->input('shipping_name'), 
                'shipping_address' => $request->input('shipping_address'), 
                'shipping_city' => $request->input('shipping_city'), 
                'shipping_zip' => $request->input('shipping_zip'), 
                'amount_cents' => 5000, 
                'stripe_payment_id'=> $paymentIntent->id, 
                'status' => 'paid' 
            ]); 

            return response()->json([ 
                'success' => true, 
                'message' => 'Order processed and recorded successfully!', 
                'order_id'=> $order->id 
            ]); 

        } catch (\Exception $e) { 
            return response()->json([ 
                'success' => false, 
                'error' => $e->getMessage() 
            ], 500); 
        } 
    } 
}
