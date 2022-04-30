<?php

namespace App\Policies;

use App\Models\StatusKumuh;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusKumuhPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list status kumuh');
    }

    public function view(User $user,StatusKumuh $status)
    {
        return $user->hasPermissionTo('view status kumuh');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create status kumuh');
    }

    public function update(User $user,StatusKumuh $status)
    {
        return $user->hasPermissionTo('update status kumuh');
    }

    public function delete(User $user,StatusKumuh $status)
    {
        return $user->hasPermissionTo('delete status kumuh');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete status kumuh');
    }

    public function restore(User $user,StatusKumuh $status)
    {
        return false;
    }

    public function forceDelete(User $user,StatusKumuh $status)
    {
        return false;
    }
}
