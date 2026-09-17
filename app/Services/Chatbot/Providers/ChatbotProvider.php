<?php

namespace App\Services\Chatbot\Providers;

interface ChatbotProvider
{
  public function chat(array $messages): string;
}
