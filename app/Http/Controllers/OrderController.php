<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Services\SMSService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function store(Request $request)
    {

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|in:10000000,12000000,15000000,20000000',
            'invoice_count' => 'required|in:6,9,12',
        ]);

        $customer = Customer::find($request->customer_id);

        if ($customer->status == 'blocked' || !$customer->complete_info) {
            return response()->json(['error' => 'Customer is not eligible to place an order.'], 400);
        }

        $orderStatus = $customer->bank_account_number ? 'CHECK_HAVING_ACCOUNT' : 'OPENING_BANK_ACCOUNT';

        $order = Order::create([
            'customer_id' => $customer->id,
            'amount' => $request->amount,
            'invoice_count' => $request->invoice_count,
            'status' => $orderStatus,
        ]);

        $message = "Dear {$customer->name},\nYour order has been registered successfully.\nThank you.";
        $this->smsService->send($customer->mobile, $message);

        return response()->json(['message' => 'Order registered successfully.', 'order' => $order]);
    }
}
