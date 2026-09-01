<?php

namespace App\Policies;

use App\Models\User;

class ProfitLossPolicy
{
    public function view(User $user): bool
    {
        return $user->can('profit-loss.view');
    }
}
