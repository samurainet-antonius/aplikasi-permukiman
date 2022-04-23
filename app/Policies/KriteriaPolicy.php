<?php

namespace App\Policies;

use App\Models\Kriteria;
use Illuminate\Auth\Access\HandlesAuthorization;

class KriteriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(Kriteria $kriteria)
    {
        return $kriteria->hasPermissionTo('list kriteria');
    }

    public function view(Kriteria $kriteria)
    {
        return $kriteria->hasPermissionTo('view kriteria');
    }

    public function create(Kriteria $kriteria)
    {
        return $kriteria->hasPermissionTo('create kriteria');
    }

    public function update(Kriteria $kriteria)
    {
        return $kriteria->hasPermissionTo('update kriteria');
    }

    public function delete(Kriteria $kriteria)
    {
        return $kriteria->hasPermissionTo('delete kriteria');
    }

    public function deleteAny(Kriteria $kriteria)
    {
        return $kriteria->hasPermissionTo('delete kriteria');
    }

    public function restore(Kriteria $kriteria)
    {
        return false;
    }

    public function forceDelete(Kriteria $kriteria)
    {
        return false;
    }
}
