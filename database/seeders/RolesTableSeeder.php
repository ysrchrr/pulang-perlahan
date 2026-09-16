<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'role_name' => 'Superadmin',
                'slug_name' => 'superadmin',
                'is_active' => '1',
                'created_at' => '2026-01-12 14:48:32',
                'updated_at' => '2026-01-14 14:08:58',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'role_name' => 'Admin Prodi',
                'slug_name' => 'admin_prodi',
                'is_active' => '1',
                'created_at' => '2026-01-12 14:48:50',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'role_name' => 'Bagiam Umum & Perlengkapan',
                'slug_name' => 'bagian_umum',
                'is_active' => '1',
                'created_at' => '2026-01-12 14:49:03',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'role_name' => 'Wakil Dekan',
                'slug_name' => 'wakil_dekan',
                'is_active' => '1',
                'created_at' => '2026-01-12 14:49:14',
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}