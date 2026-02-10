<?php

namespace App\Services\Messaging;

use App\Models\UserBlock;

class BlockGuardService
{
    public function blockedEitherWay(int $a, int $b): bool
    {
        return UserBlock::query()
            ->where(fn ($q) => $q->where('blocker_id', $a)->where('blocked_id', $b))
            ->orWhere(fn ($q) => $q->where('blocker_id', $b)->where('blocked_id', $a))
            ->exists();
    }
}
