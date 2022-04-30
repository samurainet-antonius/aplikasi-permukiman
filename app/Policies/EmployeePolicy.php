<?php

namespace App\Policies;

use App\Models\Petugas;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('list staff');
    }

    public function view(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('view staff');
    }

    public function create(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('create staff');
    }

    public function update(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('update staff');
    }

    public function delete(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('delete staff');
    }

    public function deleteAny(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('delete staff');
    }

    public function restore(Petugas $petugas)
    {
        return false;
    }

    public function forceDelete(Petugas $petugas)
    {
        return false;
    }
}
