<?php

namespace App\Services\SMS;

interface SMSDriverInterface
{
    public function send($receptor, $message);
}