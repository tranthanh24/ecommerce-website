<?php

return [
  'enabled' => env('CHATBOT_ENABLED', false),
  'log_to_db' => env('CHATBOT_LOG_TO_DB', true),

  /* Supported: "gemini", "openai" */
  'provider' => env('CHATBOT_PROVIDER', 'gemini'),

  'max_history_messages' => env('CHATBOT_MAX_HISTORY', 12),
  'memory_hot_messages' => env('CHATBOT_MEMORY_HOT_MESSAGES', 8),
  'max_product_results' => env('CHATBOT_MAX_PRODUCTS', 10),
  'synonyms_cache_minutes' => env('CHATBOT_SYNONYMS_CACHE_MINUTES', 60),
  'synonyms_max_per_key' => env('CHATBOT_SYNONYMS_MAX_PER_KEY', 12),

  'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    'temperature' => env('OPENAI_TEMPERATURE', 0.5),
    'timeout' => env('OPENAI_TIMEOUT', 30),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 1024),
  ],

  'gemini' => [
    'api_key' => env('GEMINI_API_KEY'),
    'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
    'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    'temperature' => env('GEMINI_TEMPERATURE', 0.5),
    'timeout' => env('GEMINI_TIMEOUT', 30),
    'max_output_tokens' => env('GEMINI_MAX_OUTPUT_TOKENS', 1024),
    'top_p' => env('GEMINI_TOP_P', 0.95),
    'top_k' => env('GEMINI_TOP_K', 40),
  ],
];
