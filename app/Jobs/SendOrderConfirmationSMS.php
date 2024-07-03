<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Customer;
use App\Services\SMSService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderConfirmationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $customer;
    public $message;
    /**
     * Create a new job instance.
     */
    public function __construct(Customer $customer, string $message)
    {
        $this->customer = $customer;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(SMSService $smsService): void
    {
        $smsService->send($this->customer->mobile, $this->message);
    }
}
