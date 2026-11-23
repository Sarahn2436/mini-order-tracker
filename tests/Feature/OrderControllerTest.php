<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_deleting_an_order_removes_it_and_redirects_to_orders(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $order = Order::factory()->for($customer)->create();

        $response = $this->actingAs($user)->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders'));
        $response->assertSessionHas('success', 'Order deleted successfully.');
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }
}
