<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'indonesia_districts';

    protected $fillable = ['code', 'city_code', 'name', 'meta'];

    protected $searchableFields = ['*'];

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_code', 'code');
    }

    public function evaluasi()
    {
        return $this->hasOne('App\Models\Evaluasi','code', 'district_code');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
