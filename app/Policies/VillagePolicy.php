<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Village;
use Illuminate\Auth\Access\HandlesAuthorization;

class VillagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list village');
    }

    public function view(User $user,Village $village)
    {
        return $user->hasPermissionTo('view village');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create village');
    }

    public function update(User $user,Village $village)
    {
        return $user->hasPermissionTo('update village');
    }

    public function delete(User $user,Village $village)
    {
        return $user->hasPermissionTo('delete village');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete village');
    }

    public function restore(User $user,Village $village)
    {
        return false;
    }

    public function forceDelete(User $user,Village $village)
    {
        return false;
    }
}
