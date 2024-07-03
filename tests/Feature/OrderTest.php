<?php

namespace Tests\Feature;

use App\Jobs\SendOrderConfirmationSMS;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_registration_success()
    {
        Queue::fake(); 
        $customer = Customer::factory()->create([
            'bank_account_number' => '1234567890',
            'status' => 'normal',
            'complete_info' => true,
            'mobile' => '1234567890',
            'name' => 'John Doe',
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'amount' => 10000000,
            'invoice_count' => 6,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Order registered successfully.']);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'amount' => 10000000,
            'invoice_count' => 6,
            'status' => 'CHECK_HAVING_ACCOUNT', 
        ]);

        Queue::assertPushed(SendOrderConfirmationSMS::class, function ($job) use ($customer) {
            return $job->customer->id === $customer->id;
        });


    }

    public function test_order_registration_failure()
    {
        $customer = Customer::factory()->create([
            'status' => 'blocked',
            'complete_info' => false,
        ]);

        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'amount' => 10000000,
            'invoice_count' => 6,
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Customer is not eligible to place an order.']);

        $this->assertDatabaseMissing('orders', [
            'customer_id' => $customer->id,
        ]);
    }
}