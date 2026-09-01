<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
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
        return $user->can('sales.view');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->can('sales.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sales.create');
    }

    public function complete(User $user, Sale $sale): bool
    {
        return $user->can('sales.complete');
    }

    public function cancel(User $user, Sale $sale): bool
    {
        return $user->can('sales.cancel');
    }

    public function updatePayment(User $user, Sale $sale): bool
    {
        return $user->can('sales.update-payment');
    }

    public function update(User $user, Sale $sale): bool
    {
        return $user->can('sales.edit');
    }

    public function delete(User $user, Sale $sale): bool
    {
        return $user->can('sales.delete');
    }

    public function restore(User $user, Sale $sale): bool
    {
        return $user->can('sales.restore');
    }

    public function forceDelete(User $user, Sale $sale): bool
    {
        return $user->can('sales.force-delete');
    }
}
