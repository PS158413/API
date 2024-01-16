<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Lars',
            'last_name' => 'Ferron',
            'password' => Hash::make('password'),
            'email' => 'lars@groenevingers.com',
            'city' => 'Bavaria',
            'phone' => 0000000000,
            'birthday' => '1991-01-01',
        ]);

        // make user nick
        User::create([
            'name' => 'nick',
            'last_name' => 'van hooff',
            'password' => Hash::make('password'),
            'email' => 'nick@groenevingers.com',
            'city' => 'Eindhoven',
            'phone' => 0000000000,
            'birthday' => '1991-01-01',
        ]);

        User::create([
            'name' => 'hasan',
            'last_name' => 'hussein',
            'password' => Hash::make('password'),
            'email' => 'hasan@groenevingers.com',
            'city' => 'Oirschot',
            'phone' => 0000000000,
            'birthday' => '1991-01-01',
        ]);

        User::create([
            'name' => 'gurkan',
            'last_name' => 'tasmurat',
            'password' => Hash::make('password'),
            'email' => 'gurkan@groenevingers.com',
            'city' => 'Hamont',
            'phone' => 0000000000,
            'birthday' => '1991-01-01',
        ]);

        User::create([
            'name' => 'winkelklant',
            'last_name' => 'winkel',
            'password' => Hash::make('password'),
            'email' => 'winkel@groenevingers.com',
            'city' => 'winkel',
            'phone' => 061561651,
            'birthday' => '1999-01-01',
        ]);
        // Add more users if needed
    }
}
