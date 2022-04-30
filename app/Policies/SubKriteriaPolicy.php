<?php

namespace App\Policies;

use App\Models\SubKriteria;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubKriteriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list subkriteria');
    }

    public function view(User $user,SubKriteria $subKriteria)
    {
        return $user->hasPermissionTo('view subkriteria');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create subkriteria');
    }

    public function update(User $user,SubKriteria $subKriteria)
    {
        return $user->hasPermissionTo('update subkriteria');
    }

    public function delete(User $user,SubKriteria $subKriteria)
    {
        return $user->hasPermissionTo('delete subkriteria');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete subkriteria');
    }

    public function restore(User $user,SubKriteria $subKriteria)
    {
        return false;
    }

    public function forceDelete(User $user,SubKriteria $subKriteria)
    {
        return false;
    }
}
