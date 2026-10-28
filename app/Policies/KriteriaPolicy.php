<?php

namespace App\Policies;

use App\Models\Kriteria;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KriteriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list kriteria');
    }

    public function view(User $user,Kriteria $kriteria)
    {
        return $user->hasPermissionTo('view kriteria');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create kriteria');
    }

    public function update(User $user,Kriteria $kriteria)
    {
        return $user->hasPermissionTo('update kriteria');
    }

    public function delete(User $user,Kriteria $kriteria)
    {
        return $user->hasPermissionTo('delete kriteria');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete kriteria');
    }

    public function restore(User $user,Kriteria $kriteria)
    {
        return false;
    }

    public function forceDelete(User $user,Kriteria $kriteria)
    {
        return false;
    }
}
