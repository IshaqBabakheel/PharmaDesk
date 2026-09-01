<?php

namespace App\Policies;

use App\Models\MedicineType;
use App\Models\User;

class MedicineTypePolicy
{
    /**
     * Permission prefix.
     */
    private string $permission = 'medicine-type';

    /**
     * View Any Medicine Types
     */
    public function viewAny(User $user): bool
    {
        return $user->can($this->permission . '.view');
    }

    /**
     * View Medicine Type
     */
    public function view(User $user, MedicineType $medicineType): bool
    {
        return $user->can($this->permission . '.view');
    }

    /**
     * Create Medicine Type
     */
    public function create(User $user): bool
    {
        return $user->can($this->permission . '.create');
    }

    /**
     * Update Medicine Type
     */
    public function update(User $user, MedicineType $medicineType): bool
    {
        return $user->can($this->permission . '.edit');
    }

    /**
     * Delete Medicine Type
     */
    public function delete(User $user, MedicineType $medicineType): bool
    {
        return $user->can($this->permission . '.delete');
    }

    /**
     * Restore Medicine Type
     */
    public function restore(User $user, MedicineType $medicineType): bool
    {
        return $user->can($this->permission . '.restore');
    }

    /**
     * Permanently Delete Medicine Type
     */
    public function forceDelete(User $user, MedicineType $medicineType): bool
    {
        return $user->can($this->permission . '.force-delete');
    }
}
