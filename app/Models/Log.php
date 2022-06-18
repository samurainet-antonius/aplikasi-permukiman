<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasRoles;
    use Notifiable;
    use Searchable;

    protected $table = 'log';

    protected $fillable = ['otoritas','users_id', 'keterangan','created_at', 'updated_at'];

    protected $searchableFields = ['*'];
}
