<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\NotificationServiceInterface;
use App\Services\SmsNotificationService;
use App\Services\WhatsAppNotificationService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            NotificationServiceInterface::class,
            SmsNotificationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
