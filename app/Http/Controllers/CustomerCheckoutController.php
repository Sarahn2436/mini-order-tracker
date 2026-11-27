<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient; 
use App\Models\Order; 
use App\Models\Customer;
use App\Models\Coupon;

class CustomerCheckoutController extends Controller 
{ 
    public function CustomerCheckout() 
    { 
        $orders = Order::all(); 
        $basketData = $this->calculateBasket();
        $totalPrice = $basketData['totalPrice'];
 
        return view('layouts.Customer_view.customercheckout', compact('orders', 'totalPrice')); 
    } 

   public function applyCoupon(Request $request)
{
    $basketData = $this->calculateBasket();

    if ($basketData['totalPrice'] <= 0) {
        return response()->json(['valid' => false, 'message' => 'Your basket is empty.']);
    }

    $coupon = Coupon::where('code', strtoupper(trim($request->input('code'))))
        ->where('is_active', true)
        ->first();

    if (!$coupon) {
        return response()->json(['valid' => false, 'message' => 'Invalid or expired coupon code.']);
    }

    // Check if the customer already used this coupon
    if ($coupon->hasBeenUsedBy(Auth::user())) {
        return response()->json(['valid' => false, 'message' => 'You have already used this coupon code.']);
    }

    $discount = $coupon->calculateDiscount($basketData['totalPrice']);
    $newTotal = max(0, $basketData['totalPrice'] - $discount);

    return response()->json([
        'valid' => true,
        'message' => 'Coupon applied! You saved $' . number_format($discount, 2),
        'new_total' => number_format($newTotal, 2)
    ]);
}


        public function add(Request $request) 
    { 
        $basketData = $this->calculateBasket();

        if ($basketData['isEmpty']) {
            return response()->json(['success' => false, 'error' => 'Your basket is empty.'], 400);
        }

        $finalPrice = $basketData['totalPrice'];
        $user = Auth::user();
        $appliedCoupon = null;

        // Re-verify coupon on the server before charging Stripe
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->input('coupon_code'))))
                ->where('is_active', true)
                ->first();

            if ($coupon) {
                // Prevent checkout if user already redeemed this coupon
                if ($coupon->hasBeenUsedBy($user)) {
                    return response()->json(['success' => false, 'error' => 'You have already used this coupon code.'], 400);
                }

                $discount = $coupon->calculateDiscount($finalPrice);
                $finalPrice = max(0, $finalPrice - $discount);
                $appliedCoupon = $coupon;
            }
        }

        // Stripe requires a minimum charge of $0.50 USD (50 cents)
        $amountInCents = max(50, (int) round($finalPrice * 100));

        $customer = Customer::firstOrCreate(
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
                'product' => $basketData['productString'],   
                'quantity' => $basketData['totalQuantity'],   
                'unit_price' => $basketData['unitPrice'],
                'customer_name' => $request->input('shipping_name'), 
                'shipping_address' => $request->input('shipping_address'), 
                'shipping_city' => $request->input('shipping_city'), 
                'shipping_zip' => $request->input('shipping_zip'), 
                'amount_cents' => $amountInCents, // Saves the discounted amount charged
                'stripe_payment_id' => $paymentIntent->id, 
                'status' => 'New',
                'due_date' => now()->addDays(7)->toDateString(),
            ]); 

            // Record that this user has now used the coupon
            if ($appliedCoupon && $user) {
                $appliedCoupon->users()->attach($user->id);
            }

            session()->forget('basket');

            return response()->json([ 
                'success' => true, 
                'message' => 'Order processed and recorded successfully!', 
                'order_id' => $order->id 
            ]); 

        } catch (\Exception $e) { 
            return response()->json([ 
                'success' => false, 
                'error' => $e->getMessage() 
            ], 500); 
        } 
    }


    private function calculateBasket(): array
    {
        $basket = session()->get('basket', []);

        if (empty($basket)) {
            return [
                'isEmpty' => true,
                'productString' => '',
                'totalQuantity' => 0,
                'totalPrice' => 0,
                'unitPrice' => 0,
            ];
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

        $firstItem = reset($basket);

        return [
            'isEmpty' => false,
            'productString' => implode(', ', $productNames),
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $totalPrice,
            'unitPrice' => $firstItem['price'] ?? 0,
        ];
    }
}
