<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTemplateEvalNarasumber extends Model
{
    use SoftDeletes;

    protected $table = "master_template_evaluasi_narasumber";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function soalNarasumber()
    {
        return $this->hasMany(SoalEvaluasiNarasumber::class, 'id_template');
    }
}
