<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_price',
        'quantity',
        'product',
        'order_number',
        'customer_name',
        'shipping_address',
        'shipping_city',
        'shipping_zip',
        'amount_cents',
        'stripe_payment_id',
        'status',
        'customer_id',
    ];
}

