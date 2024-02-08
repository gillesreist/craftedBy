<?php

use App\Models\Crafter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // update existing entries
        $crafters = Crafter::all();
        foreach ($crafters as $crafter) {
            $crafter->update(['name' => fake()->unique()->word()]);
        }

        // add unique
        Schema::table('crafters', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crafters', function (Blueprint $table) {
            $table->dropUnique('crafters_name_unique');
        });
    }
};
