<?php

use App\Models\Order;
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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('stripe_id');
        });

        $orders = Order::all();
        foreach ($orders as $order) {
            $order->update(['stripe_id' => fake()->unique()->word()]);
        }

          // add unique
          Schema::table('orders', function (Blueprint $table) {
            $table->unique('stripe_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stripe_id');
        });
    }
};
