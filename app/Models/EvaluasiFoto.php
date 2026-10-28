<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class EvaluasiFoto extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'evaluasi_foto';

    protected $fillable = ['evaluasi_id','kriteria_id', 'nama_kriteria','foto','created_at','updated_at'];

    protected $searchableFields = ['*'];
}
