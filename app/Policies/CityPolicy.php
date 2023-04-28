<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list city');
    }

    public function view(User $user,City $city)
    {
        return $user->hasPermissionTo('view city');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create city');
    }

    public function update(User $user,City $city)
    {
        return $user->hasPermissionTo('update city');
    }

    public function delete(User $user,City $city)
    {
        return $user->hasPermissionTo('delete city');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete city');
    }

    public function restore(User $user,City $city)
    {
        return false;
    }

    public function forceDelete(User $user,City $city)
    {
        return false;
    }
}
