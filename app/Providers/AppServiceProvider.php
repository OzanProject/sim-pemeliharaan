<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share frontend settings globally
        try {
            $frontendSettings = \App\Models\FrontendSetting::all()->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('frontendSettings', $frontendSettings);
        } catch (\Exception $e) {
            // Abaikan jika tabel belum di-migrate
        }

        // Terapkan konfigurasi SMTP dari database ke config Laravel
        try {
            $mailSettings = Setting::where('group', 'mail')->pluck('value', 'key');

            if ($mailSettings->isNotEmpty()) {
                Config::set('mail.mailers.smtp.host', $mailSettings->get('mail_host', config('mail.mailers.smtp.host')));
                Config::set('mail.mailers.smtp.port', $mailSettings->get('mail_port', config('mail.mailers.smtp.port')));
                Config::set('mail.mailers.smtp.encryption', $mailSettings->get('mail_encryption', config('mail.mailers.smtp.encryption')));
                Config::set('mail.mailers.smtp.username', $mailSettings->get('mail_username', config('mail.mailers.smtp.username')));
                Config::set('mail.mailers.smtp.password', $mailSettings->get('mail_password', config('mail.mailers.smtp.password')));
                Config::set('mail.from.address', $mailSettings->get('mail_from_address', config('mail.from.address')));
                Config::set('mail.from.name', $mailSettings->get('mail_from_name', config('mail.from.name')));
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel settings belum ada (fresh install)
        }
    }
}
