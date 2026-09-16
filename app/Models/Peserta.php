<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peserta extends Model
{
    use SoftDeletes;

    protected $table = "peserta";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    function golongan()
    {
        return $this->belongsTo(Ref_Golongan::class, 'id_golongan');
    }
}
