<?php

namespace App\Services\SMS;

use Illuminate\Support\Facades\Http;
use App\Services\SMS\SMSDriverInterface;

class ProviderSecondSMS implements SMSDriverInterface
{
    public function send($receptor, $message)
    {
        $response = Http::post('https://api.providerSecond.com/send', [
            'username' => 'username',
            'password' => 'password',
            'receptor' => $receptor,
            'message' => $message,
        ]);

        return $response->successful();
    }
}
