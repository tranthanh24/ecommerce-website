<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChatbotMessage;
use App\Services\Chatbot\ChatbotService;
use App\Services\Chatbot\ChatbotSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
  public function __construct(
    private readonly ChatbotService $chatbot,
    private readonly ChatbotSettings $settings
  ) {}

  public function reply(Request $request)
  {
    $validated = $request->validate([
      'message' => ['required', 'string', 'max:2000'],
      'history' => ['sometimes', 'array'],
      'history.*.role' => ['sometimes', 'string', 'in:user,assistant'],
      'history.*.content' => ['sometimes', 'string', 'max:2000'],
      'conversation_id' => ['sometimes', 'string', 'max:64'],
    ]);

    $conversationId = $validated['conversation_id'] ?? $request->session()->get('chatbot_conversation_id');
    if (!is_string($conversationId) || $conversationId === '') {
      $conversationId = Str::uuid();
    }
    $request->session()->put('chatbot_conversation_id', $conversationId);

    $reply = $this->chatbot->reply(
      $validated['message'],
      $validated['history'] ?? [],
      $request->user()
    );

    $reply = $this->sanitizeAssistantReply($reply);

    $settings = $this->settings->get();

    if ($settings['log_to_db'] ?? true) {
      $this->storeMessage($request, $conversationId, 'user', $validated['message']);
      $this->storeMessage($request, $conversationId, 'assistant', $reply, [
        'provider_enabled' => $settings['enabled'] ?? true,
        'provider' => $settings['provider'] ?? 'gemini',
      ]);
    }

    return response()->json([
      'status' => 'success',
      'conversation_id' => $conversationId,
      'reply' => $reply,
    ]);
  }

  private function storeMessage(Request $request, string $conversationId, string $role, string $content, array $meta = []): void
  {
    try {
      ChatbotMessage::create([
        'conversation_id' => $conversationId,
        'user_id' => $request->user()?->id,
        'session_id' => $request->session()->getId(),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'role' => $role,
        'content' => $content,
        'meta' => empty($meta) ? null : $meta,
      ]);
    } catch (\Throwable $e) {
    }
  }

  private function sanitizeAssistantReply(string $text): string
  {
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $text = preg_replace('/\\[([^\\]]+)\\]\\((https?:\\/\\/[^\\)]+)\\)/u', '$1 - $2', $text) ?? $text;
    $text = preg_replace('/```+/', '', $text) ?? $text;
    $text = str_replace(['**', '__', '`'], '', $text);
    $text = preg_replace('/^\\s{0,3}#{1,6}\\s+/m', '', $text) ?? $text;

    $lines = explode("\n", $text);
    foreach ($lines as $i => $line) {
      $lines[$i] = preg_replace('/^\\s*[*-]\\s+/u', '• ', $line) ?? $line;
      $lines[$i] = rtrim($lines[$i]);
    }

    $text = implode("\n", $lines);
    $text = preg_replace("/\\n{3,}/", "\n\n", $text) ?? $text;

    return trim($text);
  }
}
