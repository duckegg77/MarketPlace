<?php

namespace App\Policies;

use App\Models\SubOrder;
use App\Models\User;

class SubOrderPolicy
{
    public function view(User $user, SubOrder $subOrder): bool
    {
        return $user->id === $subOrder->vendor_id || $user->id === $subOrder->order->buyer_id || in_array($user->role, ['admin', 'moderator']);
    }
}
