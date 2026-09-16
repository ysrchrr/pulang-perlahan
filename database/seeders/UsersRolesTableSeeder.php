<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_roles')->delete();
        
        \DB::table('users_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'users_id' => 1,
                'roles_id' => 1,
                'created_at' => '2026-01-12 14:51:23',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 5,
                'users_id' => 1,
                'roles_id' => 2,
                'created_at' => '2026-01-13 13:45:31',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 6,
                'users_id' => 1,
                'roles_id' => 3,
                'created_at' => '2026-01-13 13:45:36',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 14,
                'users_id' => 4,
                'roles_id' => 1,
                'created_at' => '2026-01-14 20:53:13',
                'updated_at' => '2026-01-14 20:53:13',
            ),
            4 => 
            array (
                'id' => 15,
                'users_id' => 4,
                'roles_id' => 2,
                'created_at' => '2026-01-14 20:53:13',
                'updated_at' => '2026-01-14 20:53:13',
            ),
        ));
        
        
    }
}