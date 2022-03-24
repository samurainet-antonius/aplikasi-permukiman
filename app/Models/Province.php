<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'indonesia_provinces';

    protected $fillable = ['code', 'name', 'meta'];

    protected $searchableFields = ['*'];

    public function city()
    {
        return $this->hasMany('App\Models\City', 'code', 'province_code');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
