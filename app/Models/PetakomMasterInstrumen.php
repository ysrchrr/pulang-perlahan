<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetakomMasterInstrumen extends Model
{
    use SoftDeletes;

    protected $table = "petakom_master_instrumen";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function soalInstrumen()
    {
        return $this->hasMany(PetakomSoalInstrumen::class, 'id_instrumen');
    }

    function ref_jenjang()
    {
        return $this->belongsTo(RefJenjangPetakom::class, 'jenjang');
    }

    function ref_mapel()
    {
        return $this->belongsTo(RefMapelPetakom::class, 'mapel');
    }

    function ref_enrollment()
    {
        return $this->hasMany(PetakomEnrollment::class, 'id_instrumen');
    }
}
