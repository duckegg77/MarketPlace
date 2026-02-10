<?php

namespace App\Providers;

use App\Events\SubOrderReleased;
use App\Listeners\SendReleaseNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubOrderReleased::class => [SendReleaseNotification::class],
    ];
}
