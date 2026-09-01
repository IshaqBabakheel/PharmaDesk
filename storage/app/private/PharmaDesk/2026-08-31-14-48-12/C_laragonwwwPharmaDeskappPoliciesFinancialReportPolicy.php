<?php

namespace App\Policies;

use App\Models\User;

class FinancialReportPolicy
{
    public function view(User $user) : bool
    {
       return $user->can('financial-reports.view');
    }
}
