<?php

namespace App\Policies;

use App\Models\StatusKriteria;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusKriteriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list status kriteria');
    }

    public function view(User $user,StatusKriteria $status)
    {
        return $user->hasPermissionTo('view status kriteria');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create status kriteria');
    }

    public function update(User $user,StatusKriteria $status)
    {
        return $user->hasPermissionTo('update status kriteria');
    }

    public function delete(User $user,StatusKriteria $status)
    {
        return $user->hasPermissionTo('delete status kriteria');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete status kriteria');
    }

    public function restore(User $user,StatusKriteria $status)
    {
        return false;
    }

    public function forceDelete(User $user,StatusKriteria $status)
    {
        return false;
    }
}
