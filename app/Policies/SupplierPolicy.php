<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    /**
     * View Any
     */
    public function viewAny(User $user): bool
    {
        return $user->can('suppliers.view');
    }

    /**
     * View
     */
    public function view(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.view');
    }

    /**
     * Create
     */
    public function create(User $user): bool
    {
        return $user->can('suppliers.create');
    }

    /**
     * Update
     */
    public function update(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.edit');
    }

    /**
     * Delete
     */
    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.delete');
    }

    /**
     * Restore
     */
    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.restore');
    }

    /**
     * Force Delete
     */
    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.force-delete');
    }
}