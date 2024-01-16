<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = ['rood', 'blauw', 'groen', 'geel', 'paars']; // Array met mogelijke kleuren

        for ($i = 1; $i <= 50; $i++) {
            $price = rand(10, 100); // Willekeurige prijs tussen 10 en 100
            $stock = rand(0, 50); // Willekeurige voorraad tussen 0 en 50
            $color = $colors[array_rand($colors)]; // Willekeurige kleur uit de array

            DB::table('products')->insert([
                'sku' => 'SKU'.'0000'.$i,
                'article_number' => $i,
                'name' => 'Product '.$i,
                'description' => 'Dit is product '.$i,
                'price' => $price,
                'image' => 'https://via.placeholder.com/640x480.png/03001e?text=Product'.$i,
                'color' => $color,
                'height_cm' => 10,
                'width_cm' => 5,
                'depth_cm' => 15,
                'weight_gr' => 200,
                'barcode' => $i,
                'stock' => $stock,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
