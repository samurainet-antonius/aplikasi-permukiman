<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'pilihan_jawaban';

    protected $fillable = ['subkriteria_id','jawaban', 'skor'];

    protected $searchableFields = ['*'];
}
