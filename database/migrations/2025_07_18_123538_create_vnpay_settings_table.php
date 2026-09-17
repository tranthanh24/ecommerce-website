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
    Schema::create('vnpay_settings', function (Blueprint $table) {
      $table->id();
      $table->boolean('status');
      $table->text('tmn_code');
      $table->text('hash_secret');
      $table->text('return_url');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('vnpay_settings');
  }
};
