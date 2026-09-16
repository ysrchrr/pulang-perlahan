<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsensiPeserta extends Model
{
    use SoftDeletes;

    protected $table = "absensi_peserta";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function kelas()
    {
        return $this->belongsTo(KegiatanKelas::class, 'id_kelas');
    }

    public function pesertaTerpilih()
    {
        return $this->belongsTo(V_peserta_terpilih::class, 'id_peserta_terpilih');
    }
}
