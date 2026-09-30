<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique(); // kept in the visitor's session
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 120)->nullable();
            $table->string('contact', 160)->nullable();
            $table->string('locale', 5)->default('ru');
            $table->string('page_url', 500)->nullable();
            $table->string('status', 20)->default('open');
            $table->unsignedInteger('unread_admin')->default(0);
            $table->unsignedInteger('unread_visitor')->default(0);
            $table->timestamp('last_message_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_conversation_id')->constrained()->cascadeOnDelete();
            $table->string('sender', 10); // visitor | admin
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
            $table->index(['chat_conversation_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
    }
};
