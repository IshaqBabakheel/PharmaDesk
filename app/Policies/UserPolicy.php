<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * View Any Users
     */
    public function viewAny(User $user): bool
    {
        return $user->can('users.view');
    }

    /**
     * View User
     */
    public function view(User $user, User $model): bool
    {
        return $user->can('users.view');
    }

    /**
     * Create User
     */
    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    /**
     * Update User
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('users.edit');
    }

    /**
     * Delete User
     */
    public function delete(User $user, User $model): bool
    {
        if ($model->hasRole('Super Admin')) {

            return false;
        }

        return $user->can('users.delete');
    }

    /**
     * Restore User
     */
    public function restore(User $user, User $model): bool
    {
        return $user->can('users.restore');
    }

    /**
     * Force Delete User
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($model->hasRole('Super Admin')) {

            return false;
        }

        return $user->can('users.force-delete');
    }
}