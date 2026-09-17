<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VnpaySetting extends Model
{
  use HasFactory;

  protected $fillable = [
    'id',
    'status',
    'tmn_code',
    'hash_secret',
    'return_url',
  ];

  protected $casts = [
    'status' => 'integer',
  ];
}
