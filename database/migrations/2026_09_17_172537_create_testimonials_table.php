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
        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('avatar_initials', 10)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
