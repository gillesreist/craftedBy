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

            ->has(Sku::factory(3)
                ->hasImages(2))

            ->create()

            // Attach categories and materials to each product.
            ->each(function (Product $product) {

                $categories = Category::inRandomOrder()->limit(2)->get();
                $product->categories()->attach($categories);                
                $materials = Material::inRandomOrder()->limit(2)->get();
                $product->materials()->attach($materials);

                // Attach attributes to each sku with attribute_value field in pivot
                $product->skus->each(function (Sku $sku) {
                    $attributes = Attribute::inRandomOrder()->take(rand(1, 3))->get();
                    $sku->attributes()->attach($attributes, ['attribute_value' => fake()->word()]);
                });
            });
    }
}
