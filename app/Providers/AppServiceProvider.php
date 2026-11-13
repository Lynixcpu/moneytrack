<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Force HTTPS URL scheme when on production or behind Railway proxy
        if (config('app.env') === 'production' 
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (request()->header('X-Forwarded-Proto') === 'https')
            || str_contains(request()->getHost(), 'railway.app')
        ) {
            URL::forceScheme('https');
        }
    }
}
