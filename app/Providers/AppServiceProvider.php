<?php

namespace App\Providers;

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
    // Paksa HTTPS saat di Azure (Production)
    if(env('APP_ENV') !== 'local') {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
}
