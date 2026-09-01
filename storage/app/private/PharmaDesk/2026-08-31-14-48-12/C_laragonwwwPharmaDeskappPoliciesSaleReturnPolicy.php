<?php

namespace App\Policies;

use App\Models\SaleReturn;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaleReturnPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->can('sale-returns.view');
    }

    public function view(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sale-returns.create');
    }

    public function update(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.edit');
    }

    public function delete(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.delete');
    }

    public function restore(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.restore');
    }

    public function forceDelete(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.force-delete');
    }

    public function complete(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.complete');
    }

    public function cancel(User $user, SaleReturn $saleReturn): bool
    {
        return $user->can('sale-returns.cancel');
    }
}