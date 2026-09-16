<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDokumenPegawai extends Model
{
    use SoftDeletes;

    protected $table = "master_dokumen_pegawai";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
