<?php

namespace Tests\Feature;

use App\Jobs\SendOrderConfirmationSMS;
use App\Http\Controllers\OrderController;
use App\Models\Customer;
use App\Services\SMSService;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Mockery;
use Illuminate\Http\Response;
use App\Http\Requests\OrderStoreRequest;

class OrderTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }



    /**
     * Test customer not found scenario.
     *
     * @return void
     */
    public function test_customer_not_found()
    {
        $smsService = Mockery::mock(SMSService::class);

        $this->app->instance(SMSService::class, $smsService);

        $request = [
            'customer_id' => 999, 
            'amount' => 10000000,
            'invoice_count' => 6,
        ];

        $controller = new OrderController($smsService);

        $response = $controller->store(new OrderStoreRequest($request));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());

        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Customer not found.']),
            $response->getContent()
        );
    }



    /**
     * Test order registration success.
     *
     * @return void
     */

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



    /**
     * Test order registration failure due to ineligible customer.
     *
     * @return void
     */
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

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}