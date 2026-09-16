<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolesPetakom extends Model
{
    use SoftDeletes;

    protected $table = 'roles_petakom';

    protected $fillable = [
        'role_name',
        'slug_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(UserPetakom::class, 'users_roles_petakom', 'roles_id', 'users_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menus_roles', 'roles_id', 'menus_id')->withPivot('permissions')->withTimestamps();
    }
}
