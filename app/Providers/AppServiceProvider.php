<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('phoneverificationcode', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->user()?->id ?: $request->ip()),
                Limit::perDay(5)->by($request->user()?->id ?: $request->ip()),
            ];
        });
    }
}
