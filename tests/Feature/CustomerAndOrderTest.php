<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CustomerAndOrderTest extends TestCase
{
    use RefreshDatabase;


     //Test 1: Creating a customer with valid data.
    #[Test]
    public function staff_reate_customer(): void
    {
        //authenticate the staff
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

     // Test 2: Validation failing when required fields are missing or invalid.
    #[Test]
    public function staff_create_customer_fail(): void
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

     //Test 3: Cancelling an order.
     #[Test]
    public function staff_cancel_order(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create([
            'name' => 'Sarah',
            'email' => 'sarah@example.com',
            'company' => 'example-company',
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'New',
        ]);

        $response = $this->actingAs($user)->patch(route('orders.updateStatus', $order->id), [
            'status' => 'Cancelled',
        ]);

        // makes sure it redirects cleanly and updates the order status in the database
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Cancelled',
        ]);
    }
}

// to run:
// php artisan test --filter CustomerAndOrderTest


