<?php

namespace App\Policies;

use App\Models\User;

class InventoryReportPolicy
{
    public function view(User $user): bool
    {
        return $user->can('inventory-reports.view');
    }
}