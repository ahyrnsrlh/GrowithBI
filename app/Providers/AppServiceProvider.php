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
        // Note: Vite::prefetch() was removed — it eagerly loaded ALL lazy chunks
        // (vendor-chartjs, vendor-leaflet, vendor-faceapi) which defeats code splitting.
        // Lazy chunks now load only on demand.
    }
}
