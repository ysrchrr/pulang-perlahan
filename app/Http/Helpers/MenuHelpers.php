<?php

namespace App\Helpers;

use App\Models\Menu;
use App\Models\MenuParent;
use Illuminate\Support\Facades\DB;

class MenuHelpers
{
    public static function getMenuByRole($roleId)
    {
        return Menu::with('parent')
            ->whereHas('roles', function ($query) use ($roleId) {
                $query->where('roles.id', $roleId);
            })
            ->where('is_active', '1')
            ->orderBy('menu_order')
            ->get()
            ->groupBy('parent_id');
    }

    public static function getMenuParents()
    {
        return MenuParent::where('is_active', '1')
            ->orderBy('parent_order')
            ->get();
    }

    public static function hasPermission($menuSlug, $permission)
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Role aktif disimpan di session setelah user memilih role (choose-role)
        $roleId = session('role_id');

        if (!$roleId) {
            return false;
        }

        $menuRole = DB::table('menus_roles as a')
            ->join('roles as b', 'b.id', '=', 'a.roles_id')
            ->join('menus as c', 'c.id', '=', 'a.menus_id')
            ->where('c.slug_name', $menuSlug)
            ->where('b.id', $roleId)
            ->where('c.is_active', '1')
            ->select('a.permissions')
            ->first();

        if (!$menuRole) {
            return false;
        }

        $permissions = json_decode($menuRole->permissions, true);

        return in_array($permission, $permissions);
    }
}
