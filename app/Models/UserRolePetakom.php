<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRolePetakom extends Model
{

    protected $table = 'users_roles_petakom';

    protected $fillable = [
        'users_id',
        'roles_id',
    ];

    public function role()
    {
        return $this->belongsTo(RolesPetakom::class, 'roles_id');
    }

    public function user()
    {
        return $this->belongsTo(UserPetakom::class, 'users_id');
    }
}
