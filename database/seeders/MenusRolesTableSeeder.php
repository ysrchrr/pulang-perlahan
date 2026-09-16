<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus_roles')->delete();
        
        \DB::table('menus_roles')->insert(array (
            0 => 
            array (
                'id' => 7,
                'menus_id' => 6,
                'roles_id' => 2,
                'permissions' => '["view"]',
                'created_at' => '2026-01-16 20:06:47',
                'updated_at' => '2026-01-16 20:06:47',
            ),
            1 => 
            array (
                'id' => 8,
                'menus_id' => 6,
                'roles_id' => 3,
                'permissions' => '["view"]',
                'created_at' => '2026-01-16 20:06:47',
                'updated_at' => '2026-01-16 20:06:47',
            ),
            2 => 
            array (
                'id' => 9,
                'menus_id' => 6,
                'roles_id' => 4,
                'permissions' => '["view"]',
                'created_at' => '2026-01-16 20:06:47',
                'updated_at' => '2026-01-16 20:06:47',
            ),
            3 => 
            array (
                'id' => 10,
                'menus_id' => 7,
                'roles_id' => 2,
                'permissions' => '["view","create","edit","delete"]',
                'created_at' => '2026-01-16 20:53:08',
                'updated_at' => '2026-01-16 20:53:08',
            ),
            4 => 
            array (
                'id' => 11,
                'menus_id' => 8,
                'roles_id' => 2,
                'permissions' => '["view","create","edit","delete"]',
                'created_at' => '2026-01-16 21:47:39',
                'updated_at' => '2026-01-16 21:47:39',
            ),
            5 => 
            array (
                'id' => 12,
                'menus_id' => 8,
                'roles_id' => 3,
                'permissions' => '["view","create","edit"]',
                'created_at' => '2026-01-16 21:47:39',
                'updated_at' => '2026-01-16 21:47:39',
            ),
            6 => 
            array (
                'id' => 13,
                'menus_id' => 8,
                'roles_id' => 4,
                'permissions' => '["view","create","edit"]',
                'created_at' => '2026-01-16 21:47:39',
                'updated_at' => '2026-01-16 21:47:39',
            ),
        ));
        
        
    }
}