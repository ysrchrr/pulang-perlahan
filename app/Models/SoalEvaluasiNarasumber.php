<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoalEvaluasiNarasumber extends Model
{
    use SoftDeletes;

    protected $table = "soal_evaluasi_narasumber";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function template()
    {
        return $this->belongsTo(MasterTemplateEvalNarasumber::class, 'id_template');
    }

    function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
