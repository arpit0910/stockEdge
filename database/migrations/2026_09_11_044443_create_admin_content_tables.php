<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $t): void {
            $t->id();
            $t->string('kind');
            $t->string('name');
            $t->text('description')->nullable();
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
            $t->unique(['kind', 'name']);
        });
        Schema::create('site_pages', function (Blueprint $t): void {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('eyebrow')->nullable();
            $t->text('summary');
            $t->longText('body');
            $t->string('meta_description', 300)->nullable();
            $t->boolean('published')->default(false);
            $t->boolean('show_in_footer')->default(false);
            $t->boolean('system')->default(false);
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });
        Schema::create('site_sections', function (Blueprint $t): void {
            $t->id();
            $t->string('name');
            $t->string('layout');
            $t->string('eyebrow')->nullable();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('button_label')->nullable();
            $t->string('button_url')->nullable();
            $t->string('image_path')->nullable();
            $t->string('image_alt')->nullable();
            $t->unsignedInteger('item_limit')->default(4);
            $t->unsignedInteger('position')->default(0);
            $t->boolean('published')->default(false);
            $t->timestamps();
        });
        Schema::create('plans', function (Blueprint $t): void {
            $t->id();
            $t->string('name')->unique();
            $t->string('headline');
            $t->text('description');
            $t->text('features');
            $t->decimal('monthly_price', 10, 2)->default(0);
            $t->decimal('yearly_price', 10, 2)->default(0);
            $t->boolean('is_trial')->default(false);
            $t->boolean('featured')->default(false);
            $t->boolean('published')->default(false);
            $t->unsignedInteger('position')->default(0);
            $t->timestamps();
        });
        Schema::create('site_settings', function (Blueprint $t): void {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
        Schema::create('media_assets', function (Blueprint $t): void {
            $t->id();
            $t->string('name');
            $t->string('path')->unique();
            $t->string('alt_text');
            $t->unsignedBigInteger('size');
            $t->string('mime');
            $t->timestamps();
        });
        Schema::create('admin_activities', function (Blueprint $t): void {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('actor');
            $t->string('action');
            $t->string('resource');
            $t->unsignedBigInteger('record_id')->nullable();
            $t->string('label');
            $t->text('changed_fields')->nullable();
            $t->timestamps();
        });
        Schema::table('users', function (Blueprint $t): void {
            $t->boolean('is_active')->default(true);
        });
        Schema::table('leads', function (Blueprint $t): void {
            $t->string('status')->default('new');
            $t->text('admin_notes')->nullable();
        });
        Schema::table('subscription_requests', function (Blueprint $t): void {
            $t->text('admin_notes')->nullable();
        });
        Schema::table('articles', function (Blueprint $t): void {
            $t->string('image_path')->nullable();
            $t->string('image_alt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('articles', fn (Blueprint $t) => $t->dropColumn(['image_path', 'image_alt']));
        Schema::table('subscription_requests', fn (Blueprint $t) => $t->dropColumn('admin_notes'));
        Schema::table('leads', fn (Blueprint $t) => $t->dropColumn(['status', 'admin_notes']));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_active'));
        foreach (['admin_activities', 'media_assets', 'site_settings', 'plans', 'site_sections', 'site_pages', 'taxonomies'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
