<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Superadmin',
                'email' => 'admin',
                'google_id' => NULL,
                'avatar' => NULL,
                'email_verified_at' => NULL,
                'password' => '$2y$10$If5LQnIWZcZMM0HW4FZMresV8WZeNGmRk4.1FU6zc9NBuBa/AlTQ2',
                'remember_token' => NULL,
                'created_at' => '2026-01-12 14:51:01',
                'updated_at' => '2026-01-14 20:19:23',
                'last_login' => '2026-01-14 20:19:23',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'Muhammad Yasir Choiri',
                'email' => 'ysrchr@gmail.com',
                'google_id' => '103987523310570459389',
                'avatar' => 'https://lh3.googleusercontent.com/a/ACg8ocKb5irddXddWNpUrxA7O43vtxaOtosBKQ7xHT19HImLf6T7gYgU=s96-c',
                'email_verified_at' => NULL,
                'password' => '$2y$12$GzOHqLBKjRGKDfDXghXUJ.FSmbpQNcUOs3CjKkPVBMoT6u9TR/3JW',
                'remember_token' => NULL,
                'created_at' => '2026-01-14 20:18:52',
                'updated_at' => '2026-01-20 20:27:40',
                'last_login' => '2026-01-20 20:27:40',
            ),
        ));
        
        
    }
}