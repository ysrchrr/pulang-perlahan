<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesertaTerpilih extends Model
{
    use SoftDeletes;

    protected $table = "peserta_terpilih";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    function peserta()
    {
        return $this->belongsTo(Peserta::class, 'id_peserta');
    }
}
