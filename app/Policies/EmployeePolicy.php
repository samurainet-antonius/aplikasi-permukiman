<?php

namespace App\Policies;

use App\Models\Petugas;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('list petugas');
    }

    public function view(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('view petugas');
    }

    public function create(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('create petugas');
    }

    public function update(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('update petugas');
    }

    public function delete(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('delete petugas');
    }

    public function deleteAny(Petugas $petugas)
    {
        return $petugas->hasPermissionTo('delete petugas');
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
