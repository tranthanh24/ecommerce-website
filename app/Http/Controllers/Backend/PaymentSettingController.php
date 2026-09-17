<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaypalSetting;
use App\Models\VnpaySetting;

class PaymentSettingController extends Controller
{
  public function index()
  {
    $paypal = PaypalSetting::first();
    $vnpay = VnpaySetting::first();

    return view('admin.payment-setting.index', compact('paypal', 'vnpay'));
  }
}
