<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingSertifikat extends Model
{
    use SoftDeletes;

    protected $table = "setting_sertifikat";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    function template_sertifikat()
    {
        return $this->belongsTo(MasterTemplateSertifikat::class, 'id_template_sertifikat');
    }

    function penandatangan()
    {
        return $this->belongsTo(MasterPenandatangan::class, 'id_penandatangan');
    }
}
