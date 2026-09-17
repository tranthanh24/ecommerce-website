<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ChatbotSetting extends Model
{
  protected $fillable = [
    'enabled',
    'provider',
    'log_to_db',
    'max_history_messages',
    'max_product_results',

    'gemini_api_key',
    'gemini_base_url',
    'gemini_model',
    'gemini_temperature',
    'gemini_timeout',
    'gemini_max_output_tokens',
    'gemini_top_p',
    'gemini_top_k',

    'openai_api_key',
    'openai_base_url',
    'openai_model',
    'openai_temperature',
    'openai_timeout',
    'openai_max_tokens',
  ];

  protected $casts = [
    'enabled' => 'boolean',
    'log_to_db' => 'boolean',
    'gemini_temperature' => 'float',
    'gemini_top_p' => 'float',
    'openai_temperature' => 'float',
  ];

  public function getGeminiApiKeyAttribute($value): ?string
  {
    if (!is_string($value) || $value === '') {
      return null;
    }

    try {
      return Crypt::decryptString($value);
    } catch (\Throwable $e) {
      return null;
    }
  }

  public function setGeminiApiKeyAttribute($value): void
  {
    if (!is_string($value) || trim($value) === '') {
      $this->attributes['gemini_api_key'] = null;
      return;
    }

    $this->attributes['gemini_api_key'] = Crypt::encryptString(trim($value));
  }

  public function getOpenaiApiKeyAttribute($value): ?string
  {
    if (!is_string($value) || $value === '') {
      return null;
    }

    try {
      return Crypt::decryptString($value);
    } catch (\Throwable $e) {
      return null;
    }
  }

  public function setOpenaiApiKeyAttribute($value): void
  {
    if (!is_string($value) || trim($value) === '') {
      $this->attributes['openai_api_key'] = null;
      return;
    }

    $this->attributes['openai_api_key'] = Crypt::encryptString(trim($value));
  }
}
