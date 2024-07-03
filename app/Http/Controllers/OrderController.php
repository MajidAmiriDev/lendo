<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Services\SMSService;
use Illuminate\Http\Request;
use App\Http\Requests\OrderStoreRequest;
use App\Jobs\SendOrderConfirmationSMS;
use Illuminate\Support\Facades\Log;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @var SMSService
     */    
    protected $smsService;



    /**
     * OrderController constructor.
     * @param SMSService $smsService
     */
    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }



    /**
     * Store a newly created order in storage.
     *
     * @param OrderStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderStoreRequest $request)
    {
        $customer = Customer::find($request->customer_id);

        if(!$customer){
            return response()->json(['error' => 'Customer not found.'], 404);   
        }


        if (!$this->isCustomerEligible($customer)) {
            return response()->json(['error' => 'Customer is not eligible to place an order.'], 400);
        }

        $orderStatus = $this->setOrderStatus($customer);

        $order = Order::create([
            'customer_id' => $customer->id,
            'amount' => $request->amount,
            'invoice_count' => $request->invoice_count,
            'status' => $orderStatus,
        ]);


        if($order){
            $message = "Dear {$customer->name},\nYour order has been registered successfully.\nThank you.";
            //$this->smsService->send($customer->mobile, $message);
            SendOrderConfirmationSMS::dispatch($customer, $message)->onQueue('sms');
            Log::info('Order registered successfully.', ['order_id' => $order->id, 'customer_id' => $customer->id]);
            return response()->json(['message' => 'Order registered successfully.', 'order' => $order],200);

        }else{
            Log::error('Error registering order.', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to register order.'], 500);

        }

    }

    /**
     * Check if the customer is eligible to place an order.
     *
     * @param Customer $customer
     * @return bool
     */
    public function isCustomerEligible(Customer $customer): bool
    {
        return ($customer->status !== 'blocked' && $customer->complete_info);
    }



    /**
     * Set the order status based on the customer's information.
     *
     * @param Customer $customer
     * @return string
     */
    public function setOrderStatus(Customer $customer): string
    {
        return $customer->bank_account_number ? 'CHECK_HAVING_ACCOUNT' : 'OPENING_BANK_ACCOUNT';
    }

}
