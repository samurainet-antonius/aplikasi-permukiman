<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class StatusKriteria extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'status_kriteria';

    protected $fillable = ['tahun', 'nama', 'warna', 'nilai_min', 'nilai_max'];

    protected $searchableFields = ['*'];

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function evaluasi()
    {
        return $this->hasOne('App\Models\Evaluasi','id', 'status_id');
    }
}
