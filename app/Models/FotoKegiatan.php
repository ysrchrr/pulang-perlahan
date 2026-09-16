<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoKegiatan extends Model
{
    use SoftDeletes;

    protected $table = "foto_kegiatan";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }
}
