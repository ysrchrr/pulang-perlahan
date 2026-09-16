<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menus';

    protected $fillable = [
        'parent_id',
        'menu_name',
        'slug_name',
        'icon',
        'menu_order',
        'is_active',
        'is_restricted',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_restricted' => 'boolean',
        'menu_order' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function parent()
    {
        return $this->belongsTo(MenuParent::class, 'parent_id', 'id');
    }

    // Relation ke roles (many to many)
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'menus_roles', 'menus_id', 'roles_id')->withPivot('permissions')->withTimestamps();
    }
}
