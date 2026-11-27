<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'SAVE10',
                'type' => 'fixed',
                'value' => 10.00, // $10.00 off
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME20',
                'type' => 'percent',
                'value' => 20.00, // 20% off
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED50',
                'type' => 'percent',
                'value' => 50.00,
                'is_active' => false, // Useful for testing invalid/inactive error messages
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
