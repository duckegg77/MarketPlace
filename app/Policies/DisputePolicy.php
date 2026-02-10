<?php

namespace App\Policies;

use App\Models\Dispute;
use App\Models\User;

class DisputePolicy
{
    public function resolve(User $user, Dispute $dispute): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
}
