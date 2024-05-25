<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EasySms::class, function () {
            return new EasySms(config('sms'));
        });

        $this->app->alias(EasySms::class, 'sms');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
