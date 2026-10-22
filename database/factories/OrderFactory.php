<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer; // Added so we can link orders to existing customers
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
        return [
            'customer_id' => Customer::pluck('id')->random(),

            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),

            'product' => $this->faker->randomElement(['Laptop', 'Keyboard', 'Monitor', 'Desk Chair', 'Mouse Pad']),

            'quantity' => $this->faker->numberBetween(1, 5),

            'unit_price' => $this->faker->randomFloat(2, 10, 1200),

            'status' => $this->faker->randomElement(['New', 'In production', 'Despatched', 'Cancelled']),

            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ];
    }
}

