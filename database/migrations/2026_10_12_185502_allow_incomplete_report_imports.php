<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            $table->foreignId('stock_id')->nullable()->change();
            $table->text('import_warnings')->nullable()->after('body_format');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            $table->dropColumn('import_warnings');
            $table->foreignId('stock_id')->nullable(false)->change();
        });
    }
};
