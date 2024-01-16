<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Haal alle producten en categorieën op
        $products = Product::all();
        $categories = Category::all();

        // Loop door elk product
        foreach ($products as $product) {
            // Bepaal een willekeurig aantal categorieën om aan het product toe te voegen (tussen 1 en het totale aantal categorieën)
            $categoryCount = rand(1, $categories->count());

            // Kies willekeurige categorieën om aan het product toe te voegen
            $matchingCategories = $categories->random($categoryCount);

            // Koppel de categorieën aan het product
            foreach ($matchingCategories as $category) {
                $product->category()->attach($category);
            }
        }
    }
}
