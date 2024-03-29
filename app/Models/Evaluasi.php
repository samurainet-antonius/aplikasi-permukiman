<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'evaluasi';

    protected $fillable = ['tahun','province_code','city_code','district_code','village_code', 'status_id', 'lingkungan', 'luas_kawasan', 'luas_kumuh','jumlah_bangunan','jumlah_penduduk','jumlah_kepala_keluarga', 'latitude', 'longitude', 'gambar_delinasi','created_at','updated_at'];

    protected $searchableFields = ['*'];

    public function evaluasidetail()
    {
        return $this->hasMany('App\Models\EvaluasiDetail', 'evaluasi_id', 'id');
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
