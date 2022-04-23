<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EvaluasiDetail extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;
    use SoftDeletes;

    protected $table = 'evaluasi_detail';

    protected $fillable = ['evaluasi_id','kriteria_id', 'nama_kriteria','subkriteria_id','nama_subkriteria','jawaban','created_at','updated_at'];

    protected $searchableFields = ['*'];
}
