<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuParentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_parent')->delete();
        
        \DB::table('menu_parent')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_name' => 'Menu',
                'parent_order' => 1,
                'is_active' => '1',
                'created_at' => '2026-01-15 13:34:01',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'parent_name' => 'Manajemen',
                'parent_order' => 2,
                'is_active' => '1',
                'created_at' => '2026-01-15 13:34:09',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'parent_name' => 'Pengaduan',
                'parent_order' => 3,
                'is_active' => '1',
                'created_at' => '2026-01-15 13:34:20',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}