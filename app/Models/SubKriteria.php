<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;
    // use SoftDeletes;

    protected $table = 'subkriteria';

    protected $fillable = ['nama', 'kriteria_id', 'flag_pakai'];

    protected $searchableFields = ['*'];

    public function kriteria()
    {
        return $this->belongsTo('App\Models\Kriteria', 'kriteria_id', 'id');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
