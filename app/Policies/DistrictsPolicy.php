<?php

namespace App\Policies;

use App\Models\Districts;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DistrictsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list districts');
    }

    public function view(User $user,Districts $districts)
    {
        return $user->hasPermissionTo('view districts');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create districts');
    }

    public function update(User $user,Districts $districts)
    {
        return $user->hasPermissionTo('update districts');
    }

    public function delete(User $user,Districts $districts)
    {
        return $user->hasPermissionTo('delete districts');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete districts');
    }

    public function restore(User $user,Districts $districts)
    {
        return false;
    }

    public function forceDelete(User $user,Districts $districts)
    {
        return false;
    }
}
