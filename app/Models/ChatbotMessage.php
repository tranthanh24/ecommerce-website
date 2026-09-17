<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
  protected $fillable = [
    'conversation_id',
    'user_id',
    'session_id',
    'ip_address',
    'user_agent',
    'role',
    'content',
    'meta',
  ];

  protected $casts = [
    'meta' => 'array',
  ];
}
