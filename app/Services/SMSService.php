<?php

namespace App\Services;

use App\Services\SMS\SMSDriverInterface;

class SMSService
{
    protected $driver;

    public function __construct(SMSDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function send($receptor, $message)
    {
        return $this->driver->send($receptor, $message);
    }
}
