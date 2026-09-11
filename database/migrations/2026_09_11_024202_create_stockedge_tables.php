<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->boolean('is_admin')->default(false);
            $t->timestamp('trial_ends_at')->nullable();
        });
        Schema::create('stocks', function (Blueprint $t) {
            $t->id();
            $t->string('symbol')->unique();
            $t->string('name');
            $t->string('sector');
            $t->string('cap')->default('Blue Chip');
            $t->decimal('price', 12, 4);
            $t->decimal('change', 8, 2);
            $t->decimal('yield', 8, 2)->default(0);
            $t->text('description');
            $t->timestamps();
        });
        Schema::create('reports', function (Blueprint $t) {
            $t->id();
            $t->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('category');
            $t->string('rating')->default('Hold');
            $t->text('summary');
            $t->text('body');
            $t->boolean('premium')->default(false);
            $t->boolean('published')->default(true);
            $t->timestamps();
        });
        Schema::create('articles', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('topic');
            $t->string('image')->default('city');
            $t->text('summary');
            $t->text('body');
            $t->boolean('published')->default(true);
            $t->timestamps();
        });
        Schema::create('watchlists', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $t->decimal('alert_price', 12, 4)->nullable();
            $t->unique(['user_id', 'stock_id']);
            $t->timestamps();
        });
        Schema::create('holdings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $t->decimal('quantity', 14, 4);
            $t->decimal('buy_price', 12, 4);
            $t->timestamps();
        });
        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('email');
            $t->string('phone')->nullable();
            $t->string('type');
            $t->text('message')->nullable();
            $t->boolean('consent')->default(false);
            $t->timestamps();
        });
        Schema::create('subscription_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('plan');
            $t->string('billing');
            $t->string('status')->default('pending');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['subscription_requests', 'leads', 'holdings', 'watchlists', 'articles', 'reports', 'stocks'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['is_admin', 'trial_ends_at']));
    }
};
