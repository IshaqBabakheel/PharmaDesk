<?php

namespace App\Policies;


use App\Models\Customer;
use App\Models\User;



class CustomerPolicy
{


    /**
     * Display customers
     */
    public function viewAny(User $user): bool
    {

        return $user->can('customers.view');

    }





    /**
     * View single customer
     */
    public function view(User $user, Customer $customer): bool
    {

        return $user->can('customers.view');

    }





    /**
     * Create customer
     */
    public function create(User $user): bool
    {

        return $user->can('customers.create');

    }





    /**
     * Update customer
     */
    public function update(User $user, Customer $customer): bool
    {

        return $user->can('customers.edit');

    }





    /**
     * Delete customer
     */
    public function delete(User $user, Customer $customer): bool
    {

        return $user->can('customers.delete');

    }





    /**
     * Restore customer
     */
    public function restore(User $user, Customer $customer): bool
    {

        return $user->can('customers.restore');

    }





    /**
     * Force delete
     */
    public function forceDelete(User $user, Customer $customer): bool
    {

        return $user->can('customers.force-delete');

    }


}