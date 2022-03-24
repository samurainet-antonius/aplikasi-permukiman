<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'indonesia_villages';

    protected $fillable = ['code', 'district_code', 'name', 'meta'];

    protected $searchableFields = ['*'];

    public function district()
    {
        return $this->belongsTo('App\Models\Districts', 'district_code', 'code');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
