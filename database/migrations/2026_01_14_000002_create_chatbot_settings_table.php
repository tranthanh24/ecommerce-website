<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('chatbot_settings', function (Blueprint $table) {
      $table->id();
      $table->boolean('enabled')->default(true);
      $table->string('provider')->default('gemini');
      $table->boolean('log_to_db')->default(true);
      $table->unsignedInteger('max_history_messages')->default(12);
      $table->unsignedInteger('max_product_results')->default(10);

      // Gemini
      $table->text('gemini_api_key')->nullable();
      $table->string('gemini_base_url')->default('https://generativelanguage.googleapis.com/v1beta');
      $table->string('gemini_model')->default('gemini-2.5-flash');
      $table->double('gemini_temperature')->default(0.3);
      $table->unsignedInteger('gemini_timeout')->default(30);
      $table->unsignedInteger('gemini_max_output_tokens')->default(1024);
      $table->double('gemini_top_p')->default(0.95);
      $table->unsignedInteger('gemini_top_k')->default(40);

      // OpenAI (optional)
      $table->text('openai_api_key')->nullable();
      $table->string('openai_base_url')->default('https://api.openai.com/v1');
      $table->string('openai_model')->default('gpt-4o-mini');
      $table->double('openai_temperature')->default(0.3);
      $table->unsignedInteger('openai_timeout')->default(30);
      $table->unsignedInteger('openai_max_tokens')->default(1024);

      $table->timestamps();
    });

    if (DB::table('chatbot_settings')->count() === 0) {
      DB::table('chatbot_settings')->insert([
        'id' => 1,
        'enabled' => 0,
        'provider' => 'gemini',
        'log_to_db' => 1,
        'max_history_messages' => 12,
        'max_product_results' => 10,
        'gemini_api_key' => null,
        'gemini_base_url' => 'https://generativelanguage.googleapis.com/v1beta',
        'gemini_model' => 'gemini-1.5-flash',
        'gemini_temperature' => 0.3,
        'gemini_timeout' => 30,
        'gemini_max_output_tokens' => 500,
        'gemini_top_p' => 0.95,
        'gemini_top_k' => 40,
        'openai_api_key' => null,
        'openai_base_url' => 'https://api.openai.com/v1',
        'openai_model' => 'gpt-4o-mini',
        'openai_temperature' => 0.3,
        'openai_timeout' => 30,
        'openai_max_tokens' => 500,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('chatbot_settings');
  }
};
