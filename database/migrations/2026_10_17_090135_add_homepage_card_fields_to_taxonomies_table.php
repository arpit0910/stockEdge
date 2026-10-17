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
        Schema::table('taxonomies', function (Blueprint $table): void {
            $table->string('display_name')->nullable()->after('name');
            $table->string('label')->nullable()->after('display_name');
            $table->string('image_path')->nullable()->after('description');
            $table->string('image_alt')->nullable()->after('image_path');
            $table->boolean('published')->default(true)->after('position');
        });

        $cards = [
            'Daily Analysis' => ['Daily Recommendations', 'DAILY DESK', 'Timely updates and intraday catalyst analysis on active ASX stocks.'],
            'Dividend Investor' => ['Dividend Report', 'INCOME INVESTING', 'High-yield, fully franked dividend champions with robust free cash flow.'],
            'Resources' => ['Resources & Energy', 'COMMODITIES', 'Deep-dive research on critical minerals, copper, gold, and energy transition.'],
            'Growth' => ['Growth Report', 'HIGH CONVICTION', 'High-upside mid and small-cap compounders with compounding earnings.'],
        ];

        foreach ($cards as $name => [$displayName, $label, $description]) {
            DB::table('taxonomies')
                ->where('kind', 'category')
                ->where('name', $name)
                ->update([
                    'display_name' => $displayName,
                    'label' => $label,
                    'description' => $description,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxonomies', function (Blueprint $table): void {
            $table->dropColumn(['display_name', 'label', 'image_path', 'image_alt', 'published']);
        });
    }
};
