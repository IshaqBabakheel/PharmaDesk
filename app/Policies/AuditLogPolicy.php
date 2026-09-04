<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;

class AuditLogPolicy
{
    public function view(User $user, Activity $backup): bool
    {
        return $user->can('audit-logs.view');
    }
}
