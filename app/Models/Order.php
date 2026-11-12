<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'order_number',
        'product',
        'quantity',
        'unit_price',
        'status',
        'due_date'
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function refreshStatuses(): void
{
    self::where('status', 'New')
        ->where('created_at', '<=', now()->subMinutes(1))
        ->update(['status' => 'In production']);

    self::where('status', 'In production')
        ->where('created_at', '<=', now()->subMinutes(2))
        ->update(['status' => 'Despatched']);
}

}

