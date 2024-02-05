<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Customization;
use App\Models\Material;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(100)

            // Pour chaque produit créé, je génère 3 variations
            ->has(Sku::factory(3)
                ->hasImages(2))

            ->create()

            // Pour chaque produit, je lui attache des categories et materials.
            ->each(function (Product $product) {

                $categories = Category::inRandomOrder()->limit(2)->get();
                $product->categories()->attach($categories);                
                $materials = Material::inRandomOrder()->limit(2)->get();
                $product->materials()->attach($materials);

                // Pour chaque sku du produit, j'attache des attributes en remplissant le champ 'attribute_value'
                $product->skus->each(function (Sku $sku) {
                    $attributes = Attribute::inRandomOrder()->take(rand(1, 3))->get();
                    $sku->attributes()->attach($attributes, ['attribute_value' => fake()->word()]);
                });
            });
    }
}
