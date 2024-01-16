<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Kamerplanten',
            'Tuinplanten',
            'Vetplanten',
            'Bloeiende planten',
            'Bladplanten',
            'Cactussen',
            'Bonsai',
            'Kruiden',
            'Orchideeën',
            'Bollen',
            'Vaste planten',
            'Eenjarige planten',
            'Luchtplanten',
            'Waterplanten',
            'Varens',
            'Heesters',
            'Klimplanten',
            'Bamboe',
            'Vleesetende planten',
            'Exotische planten',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'category' => $category,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
