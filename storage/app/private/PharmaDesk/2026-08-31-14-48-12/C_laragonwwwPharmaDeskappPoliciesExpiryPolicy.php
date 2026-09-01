<?php

namespace App\Policies;

use App\Models\User;

class ExpiryPolicy
{
    /**
     * View expiry management.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('expiry.view');
    }

    /**
     * View expiry records.
     */
    public function view(User $user): bool
    {
        return $user->can('expiry.view');
    }
}