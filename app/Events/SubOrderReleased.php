<?php

namespace App\Events;

use App\Models\SubOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubOrderReleased
{
    use Dispatchable, SerializesModels;

    public function __construct(public SubOrder $subOrder)
    {
    }
}
