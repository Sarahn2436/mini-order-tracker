<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement([
            'New Order',
            'Order In Production',
            'Order Dispatched',
            'Order Cancelled',
        ]);

        return [
            'customer_id' => Customer::factory(),
            'order_number' => 'ORD-'.fake()->unique()->numerify('######'),
            'product' => fake()->words(3, true),
            'quantity' => fake()->numberBetween(1, 100),
            'unit_price' => fake()->randomFloat(2, 1, 1000),
            'status' => $status,
            'due_date' => match ($status) {
                'Order Cancelled' => null,
                'Order Dispatched' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
                default => fake()->optional()->dateTimeBetween('now', '+30 days')?->format('Y-m-d'),
            },
        ];
    }
}
