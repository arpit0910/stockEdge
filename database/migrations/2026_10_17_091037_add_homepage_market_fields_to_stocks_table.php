<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stocks', function (Blueprint $table): void {
            $table->decimal('day_high', 12, 4)->nullable()->after('price');
            $table->decimal('day_low', 12, 4)->nullable()->after('day_high');
            $table->decimal('buy_price', 12, 4)->nullable()->after('yield');
            $table->decimal('target_price', 12, 4)->nullable()->after('buy_price');
            $table->string('recommendation_status')->default('Active')->after('target_price');
            $table->boolean('show_on_homepage')->default(true)->after('recommendation_status');
            $table->unsignedInteger('homepage_position')->default(0)->after('show_on_homepage');
        });

        DB::table('stocks')->orderBy('id')->get(['id', 'price', 'change'])->each(function (object $stock): void {
            $price = (float) $stock->price;
            $movement = $price * abs((float) $stock->change) / 100;
            DB::table('stocks')->where('id', $stock->id)->update([
                'day_high' => $price + $movement,
                'day_low' => max(0, $price - $movement),
                'buy_price' => $price,
                'target_price' => $price,
                'homepage_position' => $stock->id,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table): void {
            $table->dropColumn(['day_high', 'day_low', 'buy_price', 'target_price', 'recommendation_status', 'show_on_homepage', 'homepage_position']);
        });
    }
};
