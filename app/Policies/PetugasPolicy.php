<?php

namespace App\Policies;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PetugasPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list staff');
    }

    public function view(User $user,Petugas $petugas)
    {
        return $user->hasPermissionTo('view staff');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create staff');
    }

    public function update(User $user,Petugas $petugas)
    {
        return $user->hasPermissionTo('update staff');
    }

    public function delete(User $user,Petugas $petugas)
    {
        return $user->hasPermissionTo('delete staff');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete staff');
    }

    public function restore(User $user,Petugas $petugas)
    {
        return false;
    }

    public function forceDelete(User $user,Petugas $petugas)
    {
        return false;
    }
}
