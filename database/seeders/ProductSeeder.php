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
                ->afterCreating(function (Sku $sku) {
                    // Pour chaque sku créé, je vais chercher des attributes et je les attache au sku
                    $attributes = Attribute::inRandomOrder()->limit(2)->get();
                    $sku->attributes()->sync($attributes);
                    // Pour chaque liaison j'ajoute une valeur à la colonne name de la table d'association
                    $sku->attributes()->each(function ($attribute) {
                        $attribute->pivot->name = 'test';
                        $attribute->pivot->save();
                    });
                })
                ->hasImages(2)
            )
            ->create()
            ->each(function (Product $product) {
                // Pour chaque produit, je lui attache des categories, material et customization.
                $categories = Category::inRandomOrder()->limit(2)->get();
                $product->categories()->attach($categories);                
                $materials = Material::inRandomOrder()->limit(2)->get();
                $product->materials()->attach($materials);
                $customization = Customization::inRandomOrder()->first();
                $product->customization()->associate($customization);
            });
    }
}
