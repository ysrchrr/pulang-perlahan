<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use SoftDeletes;

    protected $table = "kegiatan";
    protected $guarded = [];

    protected $casts = [
        'banner_img' => 'array',
        'id_eval_penyelenggaraan' => 'integer',
        'id_eval_narasumber' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function jenisKegiatan()
    {
        return $this->belongsTo(MasterJenisKegiatan::class, 'id_jenis_kegiatan');
    }
}
