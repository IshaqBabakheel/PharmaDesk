<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, $ability)
    {
        // Super Admin can do everything
        if ($user->hasRole('Super Admin')) {
            return true;
        }
    }

    /**
     * View any purchases.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('purchases.view');
    }

    /**
     * View purchase.
     */
    public function view(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.view');
    }

    /**
     * Create purchase.
     */
    public function create(User $user): bool
    {
        return $user->can('purchases.create');
    }

    /**
     * Edit a purchase.
     */
    public function edit(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.edit');
    }

    /**
     * Update purchase.
     */
    public function update(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.edit');
    }

    public function complete(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.complete');
    }

    public function cancel(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.cancel');
    }

    public function updatePayment(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.update-payment');
    }

    /**
     * Delete purchase.
     */
    public function delete(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.delete');
    }

    /**
     * Restore purchase.
     */
    public function restore(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.restore');
    }

    /**
     * Permanently delete purchase.
     */
    public function forceDelete(User $user, Purchase $purchase): bool
    {
        return $user->can('purchases.force-delete');
    }
}