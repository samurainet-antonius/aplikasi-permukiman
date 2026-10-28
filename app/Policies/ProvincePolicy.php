<?php

namespace App\Policies;

use App\Models\Province;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProvincePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list province');
    }

    public function view(User $user,Province $province)
    {
        return $user->hasPermissionTo('view province');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create province');
    }

    public function update(User $user,Province $province)
    {
        return $user->hasPermissionTo('update province');
    }

    public function delete(User $user,Province $province)
    {
        return $user->hasPermissionTo('delete province');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete province');
    }

    public function restore(User $user,Province $province)
    {
        return false;
    }

    public function forceDelete(User $user,Province $province)
    {
        return false;
    }
}
