<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTemplateEvalPenyelenggaraan extends Model
{
    use SoftDeletes;

    protected $table = "master_template_evaluasi_penyelenggaraan";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function soalPenyelenggaraan()
    {
        return $this->hasMany(SoalEvaluasiPenyelenggaraan::class, 'id_template');
    }
}
