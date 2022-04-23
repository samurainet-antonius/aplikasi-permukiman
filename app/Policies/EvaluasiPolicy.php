<?php

namespace App\Policies;

use App\Models\Evaluasi;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluasiPolicy
{
    use HandlesAuthorization;

    public function viewAny(Evaluasi $evaluasi)
    {
        return $evaluasi->hasPermissionTo('list evaluasi');
    }

    public function view(Evaluasi $evaluasi)
    {
        return $evaluasi->hasPermissionTo('view evaluasi');
    }

    public function create(Evaluasi $evaluasi)
    {
        return $evaluasi->hasPermissionTo('create evaluasi');
    }

    public function update(Evaluasi $evaluasi)
    {
        return $evaluasi->hasPermissionTo('update evaluasi');
    }

    public function delete(Evaluasi $evaluasi)
    {
        return $evaluasi->hasPermissionTo('delete evaluasi');
    }

    public function deleteAny(Evaluasi $evaluasi)
    {
        return $evaluasi->hasPermissionTo('delete evaluasi');
    }

    public function restore(Evaluasi $evaluasi)
    {
        return false;
    }

    public function forceDelete(Evaluasi $evaluasi, evaluasi $model)
    {
        return false;
    }
}
