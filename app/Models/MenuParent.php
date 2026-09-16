<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuParent extends Model {
    protected $table = 'menu_parent';

    protected $fillable = [
        'parent_name',
        'parent_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'parent_order' => 'integer',
    ];

    public function menus() {
        return $this->hasMany(Menu::class, 'parent_id', 'id')
                    ->where('is_active', 1)
                    ->orderBy('menu_order');
    }
}