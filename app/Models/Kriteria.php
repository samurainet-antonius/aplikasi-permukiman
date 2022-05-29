<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;
    use SoftDeletes;

    protected $table = 'kriteria';

    protected $fillable = ['id','nama', 'flag_pakai'];

    protected $searchableFields = ['*'];

    public function subkriteria()
    {
        return $this->hasMany('App\Models\SubKriteria');
    }

    public function evaluasiDetail()
    {
        return $this->hasMany('App\Models\EvaluasiDetail');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
