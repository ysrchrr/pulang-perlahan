<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJabatanKegiatan extends Model
{

    protected $table = "ref_jabatan_kegiatan";
    protected $guarded = [];
}
