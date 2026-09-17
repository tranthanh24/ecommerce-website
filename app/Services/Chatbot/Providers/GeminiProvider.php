<?php

namespace App\Services\Chatbot\Providers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiProvider implements ChatbotProvider
{
  public function __construct(private readonly array $config) {}

  public function chat(array $messages): string
  {
    $apiKey = $this->config['api_key'] ?? null;
    if (!$apiKey) {
      throw new RuntimeException('Missing GEMINI_API_KEY');
    }

    $system = [];
    $contents = [];

    foreach ($messages as $message) {
      $role = $message['role'] ?? null;
      $content = $message['content'] ?? null;

      if (!is_string($role) || !is_string($content)) {
        continue;
      }

      $content = trim($content);
      if ($content === '') {
        continue;
      }

      if ($role === 'system') {
        $system[] = $content;
        continue;
      }

      $contents[] = [
        'role' => $role === 'assistant' ? 'model' : 'user',
        'parts' => [['text' => $content]],
      ];
    }

    if (empty($contents)) {
      $contents[] = [
        'role' => 'user',
        'parts' => [['text' => 'Xin chào']],
      ];
    }

    $body = [
      'contents' => $contents,
      'generationConfig' => [
        'temperature' => $this->config['temperature'] ?? 0.3,
        'maxOutputTokens' => $this->config['max_output_tokens'] ?? 512,
        'topP' => $this->config['top_p'] ?? 0.95,
        'topK' => $this->config['top_k'] ?? 40,
      ],
    ];

    if (!empty($system)) {
      $body['systemInstruction'] = [
        'parts' => [['text' => implode("\n\n", $system)]],
      ];
    }

    $model = $this->config['model'] ?? 'gemini-2.5-flash';
    $endpoint = "/models/{$model}:generateContent";

    try {
      $response = Http::baseUrl((string) ($this->config['base_url'] ?? 'https://generativelanguage.googleapis.com/v1beta'))
        ->withQueryParameters(['key' => $apiKey])
        ->timeout($this->config['timeout'] ?? 30)
        ->post($endpoint, $body)
        ->throw();
    } catch (RequestException $e) {
      throw new RuntimeException('Chatbot provider request failed: ' . $e->getMessage(), 0, $e);
    }

    $parts = data_get($response->json(), 'candidates.0.content.parts');
    if (!is_array($parts)) {
      $finish = data_get($response->json(), 'candidates.0.finishReason');
      if (is_string($finish) && $finish !== '') {
        throw new RuntimeException("Chatbot provider blocked/finished: {$finish}");
      }
      throw new RuntimeException('Chatbot provider returned empty response');
    }

    $text = '';
    foreach ($parts as $part) {
      $t = $part['text'] ?? null;
      if (is_string($t)) {
        $text .= $t;
      }
    }

    $text = trim($text);
    if ($text === '') {
      throw new RuntimeException('Chatbot provider returned empty response');
    }

    return $text;
  }
}
