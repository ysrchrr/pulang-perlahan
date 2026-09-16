<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RbacMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $isPetakom = session('program') === 'petakom' || $request->routeIs('petakom-*');
        $user = $isPetakom ? auth('petakom')->user() : auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $currentRoute = $request->route()->getName();
        $userRoleSlug = session('role_slug');

        if (!$userRoleSlug) {
            abort(403, 'Role tidak ditemukan.');
        }

        if (!empty($roles) && !in_array($userRoleSlug, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke role ini.');
        }

        if ($isPetakom) {
            $petakomRouteRoles = [
                'petakom-dashboard' => ['admin_petakom'],
                'petakom-instrumen-list' => ['admin_petakom', 'user_petakom'],
                'petakom-instrumen' => ['admin_petakom', 'user_petakom'],
                'petakom-master-instrumen' => ['admin_petakom'],
            ];

            foreach ($petakomRouteRoles as $routePrefix => $allowedRoles) {
                if (
                    ($currentRoute === $routePrefix || str_starts_with($currentRoute, $routePrefix . '-')) &&
                    in_array($userRoleSlug, $allowedRoles)
                ) {
                    return $next($request);
                }
            }

            abort(403, 'Anda tidak memiliki akses ke halaman Petakom ini.');
        }

        // Ambil semua menu slug yang bisa diakses oleh role user
        $menuSlugs = DB::table('menus_roles as a')
            ->join('menus as c', 'c.id', '=', 'a.menus_id')
            ->join('roles as b', 'b.id', '=', 'a.roles_id')
            ->where('b.slug_name', $userRoleSlug)
            ->where('c.is_active', '1')
            ->pluck('c.slug_name')
            ->toArray();

        if ($currentRoute === $userRoleSlug . '-dashboard') {
            return $next($request);
        }

        // Cek apakah route saat ini cocok (exact match atau prefix match)
        // Contoh: route 'superadmin-manajemen-role-data' cocok dengan menu 'superadmin-manajemen-role'
        $hasAccess = false;
        foreach ($menuSlugs as $slug) {
            if ($currentRoute === $slug || str_starts_with($currentRoute, $slug . '-')) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized action');
        }

        return $next($request);
    }
}
