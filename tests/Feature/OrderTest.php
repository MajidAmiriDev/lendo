<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_registration()
    {
        $customer = Customer::create([
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
    }



}
