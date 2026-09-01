<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Backup;

class BackupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('backup.view');
    }

    public function view(User $user, Backup $backup): bool
    {
        return $user->can('backup.view');
    }

    public function create(User $user): bool
    {
        return $user->can('backup.create');
    }

    public function download(User $user, Backup $backup): bool
    {
        return $user->can('backup.download');
    }

    public function delete(User $user, Backup $backup): bool
    {
        return $user->can('backup.delete');
    }

}
