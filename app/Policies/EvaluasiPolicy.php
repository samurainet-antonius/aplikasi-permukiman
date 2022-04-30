<?php

namespace App\Policies;

use App\Models\Evaluasi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluasiPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list evaluasi');
    }

    public function view(User $user,Evaluasi $evaluasi)
    {
        return $user->hasPermissionTo('view evaluasi');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create evaluasi');
    }

    public function update(User $user,Evaluasi $evaluasi)
    {
        return $user->hasPermissionTo('update evaluasi');
    }

    public function delete(User $user,Evaluasi $evaluasi)
    {
        return $user->hasPermissionTo('delete evaluasi');
    }

    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete evaluasi');
    }

    public function restore(User $user,Evaluasi $evaluasi)
    {
        return false;
    }

    public function forceDelete(User $user,Evaluasi $evaluasi)
    {
        return false;
    }
}
