<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalSetting extends Model
{
  use HasFactory;

  protected $fillable = [
    'id',
    'status',
    'currency_rate',
    'client_id',
    'secret_key',
  ];

  protected $casts = [
    'status' => 'integer',
  ];
}
