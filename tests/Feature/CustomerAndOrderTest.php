<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAndOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Creating a customer with valid data.
     */
    public function test_staff_can_create_a_customer(): void
    {
        // Authenticate a staff/admin user if your route requires login
        $user = User::factory()->create();

        $customerData = [
            'name' => 'Sarah',
            'email' => 'sarah@example.com',
            'company' => 'example-company',
        ];

        $response = $this->actingAs($user)->post(route('customers.store'), $customerData);

        // makes sure it redirects cleanly and saves the customer in the database
        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseHas('customers', [
            'name' => 'Sarah',
            'email' => 'sarah@example.com',
            'company' => 'example-company',
        ]);
    }

    /**
     * Test 2: Validation failing when required fields are missing or invalid.
     */
    public function test_creating_a_customer_fails_validation_with_invalid_data(): void
    {
        $user = User::factory()->create();

        // Submit empty name and invalid email format
        $response = $this->actingAs($user)
            ->from(route('customers.create'))
            ->post(route('customers.store'), [
                'name' => '',
                'email' => 'not-a-valid-email',
                'company' => '',
            ]);

        // makes sure it redirects back with validation errors and saves nothing
        $response->assertRedirect(route('customers.create'));
        $response->assertSessionHasErrors(['name', 'email']);
        $this->assertDatabaseCount('customers', 0);
    }

    /**
     * Test 3: Deleting an order.
     */
    public function test_staff_can_delete_an_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $response = $this->actingAs($user)->delete(route('orders.destroy', $order->id));

        // makes sure it redirects cleanly and removes the order from the database
        $response->assertRedirect();
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }
}

// to run:
// php artisan test --filter CustomerAndOrderTest


