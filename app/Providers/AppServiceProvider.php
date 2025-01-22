<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function ($request) {
            return $request->user()
                ? Limit::perMinute(100)->by($request->user()->id) // Authenticated users
                : Limit::perMinute(50)->by($request->ip());       // Guests
        });
    }
}
