<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Helpers\MenuHelpers;

class MenuComposer
{
    public function compose(View $view)
    {
        
        if (auth()->check() && session()->has('role_id')) {
            $activeRoleId = session('role_id');
            
            $menuParents = MenuHelpers::getMenuParents();
            $menus = MenuHelpers::getMenuByRole($activeRoleId);
            
            $view->with('menuParents', $menuParents);
            $view->with('menus', $menus);
        } 
    }
}