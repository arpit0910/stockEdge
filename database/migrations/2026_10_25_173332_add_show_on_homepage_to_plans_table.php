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
        Schema::table('plans', function (Blueprint $table): void {
            $table->boolean('show_on_homepage')->default(false)->after('featured');
        });

        $planIds = DB::table('plans')->where('published', true)->orderBy('position')->orderBy('id')->limit(3)->pluck('id');
        DB::table('plans')->whereIn('id', $planIds)->update(['show_on_homepage' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            $table->dropColumn('show_on_homepage');
        });
    }
};
