<?php

namespace App\Policies;

use App\Models\SubKriteria;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubKriteriaPolicy
{
    use HandlesAuthorization;

    public function viewAny(SubKriteria $subKriteria)
    {
        return $subKriteria->hasPermissionTo('list subkriteria');
    }

    public function view(SubKriteria $subKriteria, SubKriteria $model)
    {
        return $subKriteria->hasPermissionTo('view subkriteria');
    }

    public function create(SubKriteria $subKriteria)
    {
        return $subKriteria->hasPermissionTo('create subkriteria');
    }

    public function update(SubKriteria $subKriteria, SubKriteria $model)
    {
        return $subKriteria->hasPermissionTo('update subkriteria');
    }

    public function delete(SubKriteria $subKriteria, SubKriteria $model)
    {
        return $subKriteria->hasPermissionTo('delete subkriteria');
    }

    public function deleteAny(SubKriteria $subKriteria)
    {
        return $subKriteria->hasPermissionTo('delete subkriteria');
    }

    public function restore(SubKriteria $subKriteria, SubKriteria $model)
    {
        return false;
    }

    public function forceDelete(SubKriteria $subKriteria, SubKriteria $model)
    {
        return false;
    }
}
