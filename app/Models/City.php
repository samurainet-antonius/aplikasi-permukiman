<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'indonesia_cities';

    protected $fillable = ['code', 'province_code', 'name', 'meta'];

    protected $searchableFields = ['*'];

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_code', 'code');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
