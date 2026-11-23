<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_customer_details_create_a_customer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('customers.store'), [
            'name' => 'Taylor Merchant',
            'email' => 'taylor@example.com',
            'company' => 'Merchant Goods',
        ]);

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success', 'Customer saved successfully.');
        $this->assertDatabaseHas('customers', [
            'name' => 'Taylor Merchant',
            'email' => 'taylor@example.com',
            'company' => 'Merchant Goods',
        ]);
    }

    public function test_invalid_customer_email_returns_validation_error_without_creating_customer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('customers.store'), [
            'name' => 'Taylor Merchant',
            'email' => 'invalid-email',
            'company' => 'Merchant Goods',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'The email field must be a valid email address.',
        ]);
        $this->assertDatabaseMissing('customers', [
            'email' => 'invalid-email',
        ]);
    }
}
