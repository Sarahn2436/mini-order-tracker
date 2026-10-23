<?php
# populates a database with fake data

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $customers = \App\Models\Customer::factory(20)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $totalOrdersNeeded = 100;
        $currentOrderCount = 0;

        while ($currentOrderCount < $totalOrdersNeeded) {
            $randomCustomer = $customers->random();

            $chunkSize = rand(1, 4);

            if ($currentOrderCount + $chunkSize > $totalOrdersNeeded) {
                $chunkSize = $totalOrdersNeeded - $currentOrderCount;
            }

            \App\Models\Order::factory($chunkSize)->create([
                'customer_id' => $randomCustomer->id,
            ]);

            $currentOrderCount += $chunkSize;
        }
    }
}
