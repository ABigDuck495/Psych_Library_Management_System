<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL; // <-- ADD THIS USE STATEMENT
use App\Http\Middleware\CheckUserRole;

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
        // CRITICAL FIX: Force HTTPS scheme for correct URL generation on Railway proxy
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Register route middleware alias for role checks
        // so we can use ->middleware(['auth','role:admin,librarian']) in routes
        Route::aliasMiddleware('role', CheckUserRole::class);
    }
}