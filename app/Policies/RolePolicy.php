<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    /**
     * Permission prefix.
     */
    private string $permission = 'roles';

    /**
     * Before authorization.
     * Super Admin bypasses all checks.
     */
    public function before(User $user): bool|null
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    /**
     * View Any
     */
    public function viewAny(User $user): bool
    {
        return $user->can("{$this->permission}.view");
    }

    /**
     * View
     */
    public function view(User $user): bool
    {
        return $user->can("{$this->permission}.view");
    }

    /**
     * Create
     */
    public function create(User $user): bool
    {
        return $user->can("{$this->permission}.create");
    }

    /**
     * Update
     */
    public function update(User $user): bool
    {
        return $user->can("{$this->permission}.edit");
    }

    /**
     * Delete
     */
    public function delete(User $user): bool
    {
        return $user->can("{$this->permission}.delete");
    }

    /**
     * Restore
     */
    public function restore(User $user): bool
    {
        return $user->can("{$this->permission}.restore");
    }

    /**
     * Force Delete
     */
    public function forceDelete(User $user): bool
    {
        return $user->can("{$this->permission}.force-delete");
    }
}