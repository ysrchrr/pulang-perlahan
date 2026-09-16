<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnggahanDokumen extends Model
{
    use SoftDeletes;

    protected $table = "unggahan_dokumen";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function pesertaTerpilih()
    {
        return $this->belongsTo(PesertaTerpilih::class, 'id_peserta_terpilih');
    }

    function dokumen()
    {
        return $this->belongsTo(Ref_Dokumen::class, 'id_ref_dokumen');
    }
}
