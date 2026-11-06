<?php
# calculates data to feed to the dashboard
namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCheckoutController extends Controller
{
    public function CustomerCheckout()
    {
        return view('layouts.Customer_view.CustomerCheckout');
    }
}