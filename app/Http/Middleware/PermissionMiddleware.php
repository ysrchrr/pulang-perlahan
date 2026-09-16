<?php

namespace App\Http\Middleware;

use App\Helpers\MenuHelpers;
use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $menuSlug, $permission)
    {
        if (!MenuHelpers::hasPermission($menuSlug, $permission)) {
            abort(403, 'Unauhthorized action');
        }

        return $next($request);
    }
}