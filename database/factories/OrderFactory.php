<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
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
        $product = $this->faker->randomElement(['Laptop', 'Keyboard', 'Monitor', 'Desk Chair', 'Mouse Pad']);

        $basePrice = match ($product) {
            'Laptop' => 850.72,
            'Monitor' => 290.99,
            'Desk Chair' => 90.39,
            'Keyboard' => 40.79,
            'Mouse Pad' => 23.99,
        };

        $quantity = match ($product) {
            'Laptop' => $this->faker->numberBetween(1, 10),
            'Mouse Pad', 'Keyboard' => $this->faker->numberBetween(1, 40),
            default => $this->faker->numberBetween(1, 20)
        };

        $full_price = $quantity * $basePrice;
        $status = $this->faker->randomElement(['New', 'In production', 'Despatched', 'Cancelled']);

        return [
            'order_number' => 'ORD-' . $this->faker->unique()->numberBetween(100000, 999999),
            'product' => $product,
            'quantity' => $quantity,
            'unit_price' => $full_price,
            'status' => $status,
            'due_date' => ($status === 'Cancelled')
                ? 'N/A'
                : $this->faker->dateTimeBetween('now', '+2 month')->format('Y-m-d'),
        ];
    }
}

