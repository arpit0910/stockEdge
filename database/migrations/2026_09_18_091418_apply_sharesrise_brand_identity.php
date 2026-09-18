<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_settings')->updateOrInsert(
            ['key' => 'brand_name'],
            ['value' => 'SharesRise', 'updated_at' => now()],
        );

        DB::table('site_settings')->updateOrInsert(
            ['key' => 'seo_title_suffix'],
            ['value' => 'SharesRise', 'updated_at' => now()],
        );

        DB::table('site_settings')
            ->whereNotNull('value')
            ->orderBy('id')
            ->each(function (object $setting): void {
                $value = str_ireplace('StockEdge', 'SharesRise', $setting->value);

                if ($value !== $setting->value) {
                    DB::table('site_settings')->where('id', $setting->id)->update([
                        'value' => $value,
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Brand identity is not reverted because administrators may edit it after deployment.
    }
};
