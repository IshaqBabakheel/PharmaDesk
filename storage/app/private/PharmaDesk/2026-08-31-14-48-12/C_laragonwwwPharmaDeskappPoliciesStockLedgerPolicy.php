<?php

namespace App\Policies;

use App\Models\User;

class StockLedgerPolicy
{
    public function view(User $user): bool
    {
        return $user->can('stock-ledger.view');
    }
}