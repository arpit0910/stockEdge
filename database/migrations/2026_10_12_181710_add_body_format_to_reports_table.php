<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('reports', function (Blueprint $table): void {
                $table->longText('body')->change();
            });
        }

        Schema::table('reports', function (Blueprint $table): void {
            $table->string('body_format', 20)->default('text')->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table): void {
            $table->dropColumn('body_format');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('reports', function (Blueprint $table): void {
                $table->text('body')->change();
            });
        }
    }
};
