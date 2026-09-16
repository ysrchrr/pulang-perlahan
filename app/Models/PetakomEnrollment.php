<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetakomEnrollment extends Model
{
    use SoftDeletes;

    protected $table = "petakom_enrollment";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    function ref_instrumen()
    {
        return $this->belongsTo(PetakomMasterInstrumen::class, 'id_instrumen');
    }

    function ref_user()
    {
        return $this->belongsTo(UserPetakom::class, 'id_users');
    }
}
