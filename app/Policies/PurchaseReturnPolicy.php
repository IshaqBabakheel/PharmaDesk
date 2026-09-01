<?php

namespace App\Policies;

use App\Models\PurchaseReturn;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseReturnPolicy
{
    use HandlesAuthorization;

    /**
     * Super Admin Override
     */
    public function before(User $user, string $ability)
    {
        if ($user->hasRole('Super Admin')) {

            return true;

        }
    }

    /**
     * View Any
     */
    public function viewAny(User $user): bool
    {
        return $user->can('purchase-returns.view');
    }

    /**
     * View
     */
    public function view(User $user, PurchaseReturn $purchaseReturn): bool
    {
        return $user->can('purchase-returns.view');
    }

    /**
     * Create
     */
    public function create(User $user): bool
    {
        return $user->can('purchase-returns.create');
    }

    /**
     * Update
     */
    public function update(User $user, PurchaseReturn $purchaseReturn): bool
    {
        return $user->can('purchase-returns.edit');
    }

    /**
     * Delete
     */
    public function delete(User $user, PurchaseReturn $purchaseReturn): bool
    {
        return $user->can('purchase-returns.delete');
    }

    /**
     * Restore
     */
    public function restore(User $user, PurchaseReturn $purchaseReturn): bool
    {
        return $user->can('purchase-returns.restore');
    }

    /**
     * Force Delete
     */
    public function forceDelete(User $user, PurchaseReturn $purchaseReturn): bool
    {
        return $user->can('purchase-returns.force-delete');
    }
}