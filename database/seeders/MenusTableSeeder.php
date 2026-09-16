<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'id' => 6,
                'parent_id' => 1,
                'menu_name' => 'Dashboard',
                'slug_name' => 'dashboard',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
                'menu_order' => 1,
                'is_active' => '1',
                'created_at' => '2026-01-16 20:06:47',
                'updated_at' => '2026-01-20 20:32:45',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 7,
                'parent_id' => 2,
                'menu_name' => 'Kategori Pengaduan',
                'slug_name' => 'kategori-pengaduan',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-icon lucide-list"><path d="M3 5h.01"/><path d="M3 12h.01"/><path d="M3 19h.01"/><path d="M8 5h13"/><path d="M8 12h13"/><path d="M8 19h13"/></svg>',
                'menu_order' => 1,
                'is_active' => '1',
                'created_at' => '2026-01-16 20:53:08',
                'updated_at' => '2026-01-16 20:53:08',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 8,
                'parent_id' => 3,
                'menu_name' => 'Pengaduan',
                'slug_name' => 'pengaduan',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-warning-icon lucide-message-circle-warning"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>',
                'menu_order' => 1,
                'is_active' => '1',
                'created_at' => '2026-01-16 21:47:39',
                'updated_at' => '2026-01-16 21:47:39',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}