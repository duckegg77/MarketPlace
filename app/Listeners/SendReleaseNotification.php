<?php

namespace App\Listeners;

use App\Events\SubOrderReleased;
use App\Notifications\EscrowReleasedNotification;

class SendReleaseNotification
{
    public function handle(SubOrderReleased $event): void
    {
        $event->subOrder->order->buyer->notify(new EscrowReleasedNotification($event->subOrder));
    }
}
