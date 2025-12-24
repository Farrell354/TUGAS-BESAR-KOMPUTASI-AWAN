<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paksa HTTPS saat di Azure (Production)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
