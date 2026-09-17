<?php

namespace App\Services\Chatbot\Providers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAIProvider implements ChatbotProvider
{
  public function __construct(private readonly array $config) {}

  public function chat(array $messages): string
  {
    $apiKey = $this->config['api_key'] ?? null;
    if (!$apiKey) {
      throw new RuntimeException('Missing OPENAI_API_KEY');
    }

    try {
      $response = Http::baseUrl($this->config['base_url'])
        ->withToken($apiKey)
        ->timeout($this->config['timeout'])
        ->post('/chat/completions', [
          'model' => $this->config['model'],
          'temperature' => $this->config['temperature'],
          'max_tokens' => $this->config['max_tokens'],
          'messages' => $messages,
        ])
        ->throw();
    } catch (RequestException $e) {
      throw new RuntimeException('Chatbot provider request failed: ' . $e->getMessage(), 0, $e);
    }

    $content = data_get($response->json(), 'choices.0.message.content');
    if (!is_string($content) || trim($content) === '') {
      throw new RuntimeException('Chatbot provider returned empty response');
    }

    return trim($content);
  }
}
