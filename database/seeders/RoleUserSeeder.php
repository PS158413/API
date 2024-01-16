<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([
            'user_id' => 1, // User ID
            'role_id' => 1, // Role ID
        ]);

        DB::table('role_user')->insert([
            'user_id' => 2, // User ID
            'role_id' => 1, // Role ID
        ]);

        DB::table('role_user')->insert([
            'user_id' => 3, // User ID
            'role_id' => 1, // Role ID
        ]);

        DB::table('role_user')->insert([
            'user_id' => 5, // User ID
            'role_id' => 4, // Role ID
        ]);
    }
}
