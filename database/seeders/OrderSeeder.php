<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ordersToCreate = max(0, 50 - Order::query()->count());

        if ($ordersToCreate > 0) {
            Order::factory()->count($ordersToCreate)->create();
        }
    }
}
