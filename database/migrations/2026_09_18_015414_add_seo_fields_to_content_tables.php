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
        foreach (['articles', 'reports', 'stocks'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->string('seo_title', 70)->nullable();
                $blueprint->string('meta_description', 170)->nullable();
            });
        }

        foreach (['seo_title_suffix' => 'SharesRise', 'default_social_image' => '/images/city.jpg', 'social_facebook' => '', 'social_x' => '', 'social_linkedin' => '', 'social_youtube' => ''] as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()],
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['articles', 'reports', 'stocks'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->dropColumn(['seo_title', 'meta_description']);
            });
        }

        DB::table('site_settings')->whereIn('key', ['seo_title_suffix', 'default_social_image', 'social_facebook', 'social_x', 'social_linkedin', 'social_youtube'])->delete();
    }
};
