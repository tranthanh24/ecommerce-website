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
    Schema::create('chatbot_messages', function (Blueprint $table) {
      $table->id();
      $table->uuid('conversation_id')->index();
      $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
      $table->string('session_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->string('role', 20);
      $table->text('content');
      $table->json('meta')->nullable();
      $table->timestamps();
      $table->index(['user_id', 'created_at']);
      $table->index(['session_id', 'created_at']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('chatbot_messages');
  }
};
