<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

            public function hasPurchasedProduct($product)
    {
        if (!is_object($product)) {
            $product = \App\Models\Product::find($product);
        }

        if (!$product) {
            return false;
        }

        return $this->orders()
            ->where('status', 'Despatched')
            ->where('product', 'LIKE', $product->name . '%') 
            ->exists();
    }


    /**
     * Define the relationship using 'customer_id' as the foreign key
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }


}
