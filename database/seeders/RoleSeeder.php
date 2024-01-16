<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'superadmin',
            'description' => 'manager',
            'user_id' => 1, // Assuming the user with ID 1 is the creator of the role
        ]);

        Role::create([
            'name' => 'staff',
            'description' => 'staff members',
            'user_id' => 1, // Assuming the user with ID 1 is the creator of the role
        ]);

        Role::create([
            'name' => 'user',
            'description' => 'customer',
            'user_id' => 1, // Assuming the user with ID 1 is the creator of the role
        ]);

        Role::create([
            'name' => 'winkelklant',
            'description' => 'klant van de winkel',
            'user_id' => 1, // Assuming the user with ID 1 is the creator of the role
        ]);
        Role::create([
            'name' => 'kassamedewerker',
            'description' => 'personeel van de kassa',
            'user_id' => 1, // Assuming the user with ID 1 is the creator of the role
        ]);

        // Add more roles if needed
    }
}
