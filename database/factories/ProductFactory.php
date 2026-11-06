<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fn(array $attributes) => $attributes['name'] ?? $this->faker->randomElement(['Laptop', 'Keyboard', 'Monitor', 'Desk Chair', 'Mouse Pad']),

            'slug' => fn(array $attributes) => Str::slug($attributes['name']) . '-' . $this->faker->unique()->numberBetween(1, 1000),

            'price' => fn(array $attributes) => $attributes['price'] ?? match ($attributes['name']) {
                'Laptop'     => 850.72,
                'Monitor'    => 290.99,
                'Desk Chair' => 90.39,
                'Keyboard'   => 40.79,
                'Mouse Pad'  => 23.99,
                default      => $this->faker->randomFloat(2, 10, 200),
            },

            'description' => $this->faker->paragraph(),

            'image' => fn(array $attributes) => $attributes['image'] ?? match ($attributes['name']) {
                'Laptop'     => 'images/laptop.webp',
                'Keyboard'   => 'images/keyboard.jpeg',
                'Monitor'    => 'images/monitor.webp',
                'Desk Chair' => 'images/chair.webp',
                'Mouse Pad'  => 'images/mousepad.webp',
                default      => $this->faker->imageUrl(), 
            },
        ];
    }
}
