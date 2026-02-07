<?php

namespace App\Notifications;

use App\Models\SubOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EscrowReleasedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly SubOrder $subOrder)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Escrow release update')
            ->line('Sub-order #'.$this->subOrder->id.' escrow has been released.');
    }

    public function toArray(object $notifiable): array
    {
        return ['sub_order_id' => $this->subOrder->id, 'status' => 'released'];
    }
}
