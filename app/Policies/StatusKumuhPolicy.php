<?php

namespace App\Policies;

use App\Models\StatusKumuh;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusKumuhPolicy
{
    use HandlesAuthorization;

    public function viewAny(StatusKumuh $status)
    {
        return $status->hasPermissionTo('list status kumuh');
    }

    public function view(StatusKumuh $status)
    {
        return $status->hasPermissionTo('view status kumuh');
    }

    public function create(StatusKumuh $status)
    {
        return $status->hasPermissionTo('create status kumuh');
    }

    public function update(StatusKumuh $status)
    {
        return $status->hasPermissionTo('update status kumuh');
    }

    public function delete(StatusKumuh $status)
    {
        return $status->hasPermissionTo('delete status kumuh');
    }

    public function deleteAny(StatusKumuh $status)
    {
        return $status->hasPermissionTo('delete status kumuh');
    }

    public function restore(StatusKumuh $status)
    {
        return false;
    }

    public function forceDelete(StatusKumuh $status)
    {
        return false;
    }
}
