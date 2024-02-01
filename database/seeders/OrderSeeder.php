<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory(20)
            ->create()

            // Attacher un ou plusieurs SKU à l'order
            ->each(function (Order $order) {
                $skus = Sku::inRandomOrder()->take(rand(1, 10))->get();

                // pour chaque association, rajouter des infos dans la table
                foreach ($skus as $sku) {
                    $name = $sku->name;
                    $unit_price = $sku->unit_price;
                    // mettre l'id d'une taxe dans la foreign key tax_id de la table order_sku
                    $tax_id = Tax::inRandomOrder()->first()->id;
                    $quantity = rand(1,5);
                    
                    $order->skus()->attach($sku, ['sku_name' => $name, 'sku_unit_price'=> $unit_price, 'tax_id'=> $tax_id, 'quantity'=> $quantity]);
                }

            });
    }
}
