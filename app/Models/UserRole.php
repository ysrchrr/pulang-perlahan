<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model {
    
    protected $table = 'users_roles';

    protected $fillable = [
        'users_id',
        'roles_id',
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'roles_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}