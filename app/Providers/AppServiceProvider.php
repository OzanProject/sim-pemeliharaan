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
        try {
            $frontendSettings = \App\Models\FrontendSetting::all()->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('frontendSettings', $frontendSettings);
        } catch (\Exception $e) {
            // Abaikan jika tabel belum di-migrate
        }
    }
}
