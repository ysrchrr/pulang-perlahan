<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPengaduan extends Model
{
    use SoftDeletes;

    protected $table = "kategori_pengaduan";
    protected $guarded = [
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'kategori_pengaduan_id');
    }
}
