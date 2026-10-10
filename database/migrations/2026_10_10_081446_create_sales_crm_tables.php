<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_sales')->default(false)->after('is_admin');
        });

        Schema::create('contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('source_lead_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->string('phone', 30)->nullable();
            $table->string('company')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('leads', function (Blueprint $table): void {
            $table->foreignId('assigned_to')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->after('assigned_to')->constrained('contacts')->nullOnDelete();
        });

        Schema::create('lead_activities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('outcome')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->text('details')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->index(['lead_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
        Schema::table('leads', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('contact_id');
            $table->dropConstrainedForeignId('assigned_to');
        });
        Schema::dropIfExists('contacts');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_sales'));
    }
};
