<?php

namespace App\Providers;

use App\Contracts\EmailServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Services\EmailService;
use App\Services\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriptionServiceInterface::class, SubscriptionService::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
    }

    public function boot(): void
    {
        //
    }
}
