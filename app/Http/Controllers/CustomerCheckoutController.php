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
        $basket = session()->get('basket', []);

        if (empty($basket)) {
            return response()->json(['success' => false, 'error' => 'Your basket is empty.'], 400);
        }

        $productNames = [];
        $totalQuantity = 0;
        $totalPrice = 0; 

        foreach ($basket as $item) {
            $qty = $item['quantity'] ?? 1;
            $price = $item['price'] ?? 0;
            
            $productNames[] = ($item['name'] ?? 'Product') . ' (x' . $qty . ')';
            $totalQuantity += $qty;
            $totalPrice += ($price * $qty);
        }

        $amountInCents = (int) round($totalPrice * 100);

        $productString = implode(', ', $productNames);

        $firstItem = reset($basket);
        $unitPrice = $firstItem['price'] ?? 0;

        $user = \Illuminate\Support\Facades\Auth::user();

        $customer = \App\Models\Customer::firstOrCreate(
            ['email' => $user->email],
            ['name' => $request->input('shipping_name')]
        );

        $stripe = new StripeClient(env('STRIPE_SECRET')); 

        try { 
            $paymentIntent = $stripe->paymentIntents->create([ 
                'amount' => $amountInCents,
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
            
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            
            $order = Order::create([ 
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'product' => $productString,   
                'quantity' => $totalQuantity,   
                'unit_price' => $unitPrice,
                'customer_name' => $request->input('shipping_name'), 
                'shipping_address' => $request->input('shipping_address'), 
                'shipping_city' => $request->input('shipping_city'), 
                'shipping_zip' => $request->input('shipping_zip'), 
                'amount_cents' => $amountInCents,
                'stripe_payment_id'=> $paymentIntent->id, 
                'status' => 'New' 
            ]); 

            session()->forget('basket');

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