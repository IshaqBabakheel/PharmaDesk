<?php

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManufacturerPolicy
{
    /**
     * Permission prefix
     */
    private string $permission = 'manufacturer';

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can($this->permission . '.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can($this->permission . '.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can($this->permission . '.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can($this->permission . '.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can($this->permission . '.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can($this->permission . '.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Manufacturer $manufacturer): bool
    {
        return $user->can($this->permission . '.force-delete');
    }
}
