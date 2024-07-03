<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SMS\ProviderFirstSMS;
use App\Services\SMS\ProviderSecondSMS;
use App\Services\SMS\ProviderThirdSMS;
use App\Services\SMS\SMSDriverInterface;
use App\Services\SMSService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SMSDriverInterface::class, function ($app) {
            return new ProviderFirstSMS();
        });

        $this->app->singleton(SMSService::class, function ($app) {
            return new SMSService($app->make(SMSDriverInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
