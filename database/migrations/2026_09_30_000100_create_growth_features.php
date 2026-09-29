<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Uploaded images live in the database: the Vercel filesystem is not persistent.
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('mime', 40);
            $table->unsignedInteger('size');
            $table->longText('data'); // base64
            $table->timestamps();
        });

        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category'); // Service::CATEGORIES
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->unsignedInteger('area')->nullable();
            $table->string('duration')->nullable();
            $table->string('summary', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('before_image')->nullable();
            $table->string('after_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('landmark')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort')->default(0);
            $table->json('translations')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('price_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->json('translations')->nullable();
            $table->timestamps();
            $table->unique(['brand_id', 'slug']);
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('date');
            $table->string('time', 5);
            $table->string('format')->default('office'); // office, online, onsite
            $table->text('comment')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
            $table->index(['date', 'time']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->text('reply')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('excerpt', 500)->nullable();
            $table->longText('body');
            $table->string('cover_image')->nullable();
            $table->string('landmark')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->json('translations')->nullable();
            $table->timestamps();
        });

        Schema::create('client_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('address')->nullable();
            $table->string('status')->default('planning'); // planning, active, paused, done
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->string('manager')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('client_project_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('status')->default('pending'); // pending, in_progress, done
            $table->date('ends_on')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('client_project_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('source')->default('form'); // form, calculator
            $table->unsignedBigInteger('estimate')->nullable();
            $table->json('details')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['source', 'estimate', 'details']);
        });
        foreach (['client_project_updates', 'client_project_stages', 'client_projects', 'posts', 'reviews',
            'appointments', 'products', 'portfolio_projects', 'media'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
