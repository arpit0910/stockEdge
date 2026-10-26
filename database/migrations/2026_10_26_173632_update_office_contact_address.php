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
            ['key' => 'contact_address'],
            ['value' => "Level 17, 123 Pitt Street, Angel Place\nSydney, NSW 2000\nAustralia", 'updated_at' => now()],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')
            ->where('key', 'contact_address')
            ->where('value', "Level 17, 123 Pitt Street, Angel Place\nSydney, NSW 2000\nAustralia")
            ->update(['value' => 'Sydney, NSW, Australia', 'updated_at' => now()]);
    }
};
