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

            // Attach one or several SKUs to order
            ->each(function (Order $order) {
                $skus = Sku::inRandomOrder()->take(rand(1, 10))->get();

                // for each association, complete field in pivot table
                foreach ($skus as $sku) {
                    $name = $sku->name;
                    $unit_price = $sku->unit_price;
                    // add tax id in foreign key tax_id of order_sku table
                    $tax_id = Tax::inRandomOrder()->first()->id;
                    $quantity = rand(1,5);
                    
                    $order->skus()->attach($sku, ['sku_name' => $name, 'sku_unit_price'=> $unit_price, 'tax_id'=> $tax_id, 'quantity'=> $quantity]);
                }

            });
    }
}
