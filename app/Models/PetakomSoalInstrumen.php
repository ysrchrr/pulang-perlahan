<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetakomSoalInstrumen extends Model
{
    use SoftDeletes;

    protected $table = "petakom_soal_instrumen";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    public function masterInstrumen()
    {
        return $this->belongsTo(PetakomMasterInstrumen::class, 'id_instrumen');
    }
}
