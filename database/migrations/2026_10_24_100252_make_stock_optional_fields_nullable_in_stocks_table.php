<?php

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
        Schema::table('stocks', function (Blueprint $table): void {
            $table->string('cap')->nullable()->default(null)->change();
            $table->text('description')->nullable()->change();
            $table->decimal('change', 8, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table): void {
            $table->string('cap')->default('Blue Chip')->change();
            $table->text('description')->nullable(false)->change();
            $table->decimal('change', 8, 2)->nullable(false)->change();
        });
    }
};
