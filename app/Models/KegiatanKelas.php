<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanKelas extends Model
{
    use SoftDeletes;

    protected $table = "kegiatan_kelas";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }
}
