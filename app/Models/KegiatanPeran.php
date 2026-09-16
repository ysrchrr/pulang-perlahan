<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanPeran extends Model
{

    use SoftDeletes;

    protected $table = "kegiatan_peran";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function jabatan()
    {
        return $this->belongsTo(MasterJabatanKegiatan::class, 'id_peran');
    }

    function kegiatan()
    {
        return $this->belongsTo(User::class, 'id_kegiatan');
    }
}
