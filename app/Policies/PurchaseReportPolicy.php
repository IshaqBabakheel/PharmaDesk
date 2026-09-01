<?php

namespace App\Policies;

use App\Models\User;

class PurchaseReportPolicy
{
    public function view(User $user): bool
    {
        return $user->can('purchase-reports.view');
    }
}
