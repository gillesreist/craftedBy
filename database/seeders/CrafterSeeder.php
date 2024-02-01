<?php

namespace Database\Seeders;

use App\Models\Crafter;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrafterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Crafter::factory(10)
            ->hasImages(5)
            ->create();    
        
    }
}
