<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileUserPetakom extends Model
{
    use SoftDeletes;

    protected $table = "profile_user_petakom";
    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
    ];

    public function users()
    {
        return $this->belongsTo(UserPetakom::class, 'id_users');
    }
}
