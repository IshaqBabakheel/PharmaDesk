<?php

namespace App\Policies;

use App\Models\StockAdjustment;
use App\Models\User;

class StockAdjustmentPolicy
{
    /**
     * View the stock adjustment list.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('stock-adjustments.view');
    }

    /**
     * View stock adjustments.
     */
    public function view(User $user, StockAdjustment $stockAdjustment): bool
    {
        return $user->can('stock-adjustments.view');
    }

    /**
     * Create a stock adjustment.
     */
    public function create(User $user): bool
    {
        return $user->can('stock-adjustments.create');
    }

    /**
     * Update a stock adjustment.
     */
    public function update(User $user, StockAdjustment $stockAdjustment): bool 
    {
        return $user->can('stock-adjustments.edit');
    }

    /**
     * Complete a stock adjustment.
     */
    public function complete(User $user, StockAdjustment $stockAdjustment): bool 
    {
        return $user->can('stock-adjustments.complete');
    }

    /**
     * Cancel a stock adjustment.
     */
    public function cancel(User $user, StockAdjustment $stockAdjustment): bool 
    {
        return $user->can('stock-adjustments.cancel');
    }

    /**
     * Delete a stock adjustment.
     */
    public function delete(User $user, StockAdjustment $stockAdjustment): bool 
    {
        return $user->can('stock-adjustments.delete');
    }

    /**
     * Restore a soft-deleted stock adjustment.
     */
    public function restore(User $user, StockAdjustment $stockAdjustment): bool 
    {
        return $user->can('stock-adjustments.restore');
    }

    /**
     * Permanently delete a stock adjustment.
     */
    public function forceDelete(User $user, StockAdjustment $stockAdjustment): bool 
    {
        return $user->can('stock-adjustments.force-delete');
    }
}