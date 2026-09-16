<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PegawaiTerpilih extends Model
{
    use SoftDeletes;

    protected $table = "pegawai_terpilih";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    function jabatan()
    {
        return $this->belongsTo(MasterJabatanKegiatan::class, 'id_jabatan_kegiatan');
    }

    function peran()
    {
        return $this->belongsTo(KegiatanPeran::class, 'id_peran');
    }
}
