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
    Schema::create('general_settings', function (Blueprint $table) {
      $table->id();
      $table->string('site_name');
      $table->string('contact_email');
      $table->string('contact_phone');
      $table->string('contact_address');
      $table->text('map');
      $table->string('currency_icon');
      $table->string('time_zone');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('general_settings');
  }
};
