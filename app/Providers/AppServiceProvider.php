<?php

namespace App\Providers;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
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
        // Logs de seguridad: se registran a partir de los eventos de autenticación de Laravel.
        Event::listen(Login::class, function (Login $event): void {
            app(SecurityLogger::class)->record(
                'login',
                'Inicio de sesión correcto.',
                $event->user->getAuthIdentifier(),
                $event->user->email ?? null,
            );
        });

        Event::listen(Logout::class, function (Logout $event): void {
            app(SecurityLogger::class)->record(
                'logout',
                'Cierre de sesión.',
                $event->user?->getAuthIdentifier(),
                $event->user?->email ?? null,
            );
        });

        Event::listen(Failed::class, function (Failed $event): void {
            app(SecurityLogger::class)->record(
                'login_failed',
                'Intento de inicio de sesión fallido.',
                null,
                $event->credentials['email'] ?? null,
            );
        });
    }
}
