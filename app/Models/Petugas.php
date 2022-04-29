<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;
    use SoftDeletes;

    protected $table = 'petugas';

    protected $fillable = ['users_id', 'province_code','city_code','district_code','village_code', 'jabatan', 'nomer_hp', 'flag_verif', 'flag_pakai', 'created_at','updated_at'];

    protected $searchableFields = ['*'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'users_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_code', 'code');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city_code', 'code');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\Districts', 'district_code', 'code');
    }

    public function village()
    {
        return $this->belongsTo('App\Models\Village', 'village_code', 'code');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\StatusKumuh', 'status_id', 'id');
    }
}
