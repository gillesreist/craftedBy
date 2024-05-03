<?php

use App\Models\Sku;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Sluggable\HasSlug;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->string('slug');
        });

        $skus = Sku::all();
        foreach ($skus as $sku) {
            $slugOptions = $sku->getSlugOptions();
            $slug = $sku->generateSlug($slugOptions);
            $sku->update(['slug' => $slug]);
        }

          // add unique
          Schema::table('skus', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skus', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
