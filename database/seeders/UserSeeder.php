<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()
        //     ->count(50)
        //     ->hasImages(0,1)
        //     ->hasOrders(rand(0,2))
        //     ->hasAddresses(rand(1,2))
        //     ->create();

        User::factory()
            ->count(1)
            ->create();
    }
}
