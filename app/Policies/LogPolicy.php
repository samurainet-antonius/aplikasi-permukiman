<?php

namespace App\Policies;

use App\Models\Log;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list log');
    }

    public function view(User $user, Log $log)
    {
        return $user->hasPermissionTo('view log');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create log');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('update log');
    }

    public function delete(User $user, Log $log)
    {
        return $user->hasPermissionTo('delete log');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete log');
    }

    public function restore(User $user, Log $log)
    {
        return false;
    }

    public function forceDelete(User $user, Log $log)
    {
        return false;
    }
}
