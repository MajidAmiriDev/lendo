<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Services\SMSService;
use Illuminate\Http\Request;
use App\Http\Requests\OrderStoreRequest;
use App\Jobs\SendOrderConfirmationSMS;


class OrderController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function store(OrderStoreRequest $request)
    {

        $customer = Customer::find($request->customer_id);

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
            return response()->json(['message' => 'Order registered successfully.', 'order' => $order]);

        }else{

            return response()->json(['message' => 'Order failed.', 'order' => $order]);

        }
    }


    public function isCustomerEligible(Customer $customer):bool
    {
        return ($customer->status !== 'blocked' && $customer->complete_info);
    }

    public function setOrderStatus(Customer $customer):string
    {
        return $customer->bank_account_number ? 'CHECK_HAVING_ACCOUNT' : 'OPENING_BANK_ACCOUNT';
    }



}
