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
            $table->boolean('show_in_latest_coverage')->default(false);
        });

        DB::table('stocks')->update(['show_in_latest_coverage' => DB::raw('show_on_homepage')]);
    }

    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table): void {
            $table->dropColumn('show_in_latest_coverage');
        });
    }
};
