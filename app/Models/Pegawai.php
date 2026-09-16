<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $table = "pegawai";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function golongan()
    {
        return $this->belongsTo(Ref_Golongan::class, 'id_golongan');
    }
}
