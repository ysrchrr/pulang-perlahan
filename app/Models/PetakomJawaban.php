<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetakomJawaban extends Model
{

    protected $table = "petakom_jawaban_user";
    const UPDATED_AT = null;

    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function ref_soal()
    {
        return $this->belongsTo(PetakomSoalInstrumen::class, 'id_soal');
    }

    function ref_user()
    {
        return $this->belongsTo(UserPetakom::class, 'id_users');
    }
}
