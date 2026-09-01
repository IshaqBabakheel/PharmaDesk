<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('expenses.view');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->can('expenses.view');
    }

    public function create(User $user): bool
    {
        return $user->can('expenses.create');
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->can('expenses.edit');
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->can('expenses.delete');
    }

    public function restore(User $user, Expense $expense): bool
    {
        return $user->can('expenses.restore');
    }

    public function forceDelete(
        User $user,
        Expense $expense
    ): bool {
        return $user->can('expenses.force-delete');
    }

    public function complete(
        User $user,
        Expense $expense
    ): bool {
        return $user->can('expenses.complete');
    }

    public function cancel(
        User $user,
        Expense $expense
    ): bool {
        return $user->can('expenses.cancel');
    }
}