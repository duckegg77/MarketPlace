<?php

namespace App\Providers;

use App\Services\Payments\PaymentProvider;
use App\Services\Payments\StripePaymentProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentProvider::class, StripePaymentProvider::class);
    }

    public function boot(): void
    {
    }
}
