<?php

namespace App\Policies;

use App\Models\User;

class SalesReportPolicy
{

    public function view(User $user): bool
    {
        return $user->can('sales-reports.view');
    }

}
