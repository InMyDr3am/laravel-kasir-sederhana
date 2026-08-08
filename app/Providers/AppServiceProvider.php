<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Pagination pakai tampilan monochrome kita, bukan Tailwind bawaan.
        Paginator::defaultView('pagination.monochrome');
        Paginator::defaultSimpleView('pagination.monochrome');
    }
}
