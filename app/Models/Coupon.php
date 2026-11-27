<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'is_active'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function hasBeenUsedBy($user): bool
    {
        if (!$user) {
            return false;
        }

        // can comment the below line out to make coipons unlimited for tests
        return $this->users()->where('user_id', $user->id)->exists();

        return false;
    }

    public function calculateDiscount($total)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $total);
        }

        if ($this->type === 'percent') {
            return round(($this->value / 100) * $total, 2);
        }

        return 0;
    }
}

