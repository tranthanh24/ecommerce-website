<?php

namespace App\Services\Chatbot;

use App\Models\ChatbotSetting;
use Illuminate\Support\Facades\Cache;

class ChatbotSettings
{
  public function clearCache(): void
  {
    Cache::forget($this->cacheKey());
  }

  public function get(): array
  {
    return Cache::remember($this->cacheKey(), now()->addMinutes(5), function () {
      $defaults = config('chatbot') ?? [];
      $row = ChatbotSetting::query()->latest('id')->first();

      if (!$row) {
        return $defaults;
      }

      $provider = $row->provider ?: ($defaults['provider'] ?? 'gemini');

      return array_replace_recursive($defaults, [
        'enabled' => $row->enabled,
        'log_to_db' => $row->log_to_db,
        'provider' => $provider,
        'max_history_messages' => $row->max_history_messages,
        'max_product_results' => $row->max_product_results,

        'gemini' => [
          'api_key' => $row->gemini_api_key,
          'base_url' => $row->gemini_base_url,
          'model' => $row->gemini_model,
          'temperature' => $row->gemini_temperature,
          'timeout' => $row->gemini_timeout,
          'max_output_tokens' => $row->gemini_max_output_tokens,
          'top_p' => $row->gemini_top_p,
          'top_k' => $row->gemini_top_k,
        ],

        'openai' => [
          'api_key' => $row->openai_api_key,
          'base_url' => $row->openai_base_url,
          'model' => $row->openai_model,
          'temperature' => $row->openai_temperature,
          'timeout' => $row->openai_timeout,
          'max_tokens' => $row->openai_max_tokens,
        ],
      ]);
    });
  }

  public function public(): array
  {
    $settings = $this->get();

    return [
      'enabled' => $settings['enabled'] ?? true,
      'provider' => $settings['provider'] ?? 'gemini',
    ];
  }

  private function cacheKey(): string
  {
    return 'chatbot.settings.v1';
  }
}
