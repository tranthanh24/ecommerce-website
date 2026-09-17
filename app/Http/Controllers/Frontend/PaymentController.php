<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\OrderProduct;
use App\Models\VnpaySetting;
use App\Models\PaypalSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
  public function index()
  {
    if (!Session::has('shipping_address') || !Session::has('shipping_method')) {
      return redirect()->route('user.checkout');
    }

    return view('frontend.pages.payment');
  }

  public function paymentSuccess()
  {
    return view('frontend.pages.payment-success');
  }

  public function storeOrder($paymentMethod, $paymentStatus, $transactionId, $paidAmount, $paidCurrencyName)
  {
    $order = new Order();
    $order->invoice_id = 'INV-' . Str::uuid();
    $order->user_id = auth()->user()->id;
    $order->sub_total = getCartTotal();
    $order->amount = getFinalPayableAmount();
    $order->currency_name = 'VND';
    $order->currency_icon = '₫';
    $order->product_qty = Cart::content()->count();
    $order->payment_method = $paymentMethod;
    $order->payment_status = $paymentStatus;
    $order->order_address = json_encode(Session::get('shipping_address'));
    $order->shipping_method = json_encode(Session::get('shipping_method'));
    $order->coupon = json_encode(Session::get('coupon'));
    $order->order_status = 'pending';
    $order->save();

    foreach (Cart::content() as $item) {
      $product = Product::find($item->id);
      $orderProduct = new OrderProduct();
      $orderProduct->order_id = $order->id;
      $orderProduct->product_id = $product->id;
      $orderProduct->vendor_id = $product->vendor_id;
      $orderProduct->product_name = $product->name;
      $orderProduct->variants = json_encode($item->options->variants);
      $orderProduct->variants_total = $item->options->variant_total;
      $orderProduct->unit_price = $item->price;
      $orderProduct->qty = $item->qty;
      $orderProduct->save();

      // Update quantity
      $product->qty = $product->qty - $item->qty;
      $product->save();
    }

    $transaction = new Transaction();
    $transaction->order_id = $order->id;
    $transaction->transaction_id = $transactionId;
    $transaction->payment_method = $paymentMethod;
    $transaction->amount = getFinalPayableAmount();
    $transaction->amount_real_currency = $paidAmount;
    $transaction->amount_real_currency_name = $paidCurrencyName;
    $transaction->save();
  }

  public function clearSession()
  {
    Cart::destroy();
    Session::forget('shipping_address');
    Session::forget('shipping_method');
    Session::forget('coupon');
  }

  // Paypal
  public function paypalConfig()
  {
    $paypalSetting = PaypalSetting::first();

    $config = [
      'mode'    => 'sandbox',
      'sandbox' => [
        'client_id'         => $paypalSetting->client_id,
        'client_secret'     => $paypalSetting->secret_key,
        'app_id'            => '',
      ],
      'live' => [
        'client_id'         => $paypalSetting->client_id,
        'client_secret'     => $paypalSetting->secret_key,
        'app_id'            => '',
      ],

      'payment_action' => 'Sale',
      'currency'       => 'USD',
      'notify_url'     => '',
      'locale'         => 'en_US',
      'validate_ssl'   => true,
    ];

    return $config;
  }

  public function payWithPaypal()
  {
    $paypalSetting = PaypalSetting::first();
    $config = $this->paypalConfig();

    $provider = new PayPalClient($config);
    $provider->getAccessToken();

    // Caculate amount
    $total = getFinalPayableAmount();
    $money = round($total / $paypalSetting->currency_rate, 2);

    $response = $provider->createOrder([
      "intent" => "CAPTURE",
      "application_context" => [
        "return_url" => route('user.paypal.success'),
        "cancel_url" => route('user.paypal.cancel'),
      ],
      "purchase_units" => [
        0 => [
          "amount" => [
            "currency_code" => "USD",
            "value" => $money
          ]
        ]
      ]
    ]);

    if (isset($response['id']) && $response['status'] == 'CREATED') {
      foreach ($response['links'] as $link) {
        if ($link['rel'] === 'approve') {
          return redirect()->away($link['href']);
        }
      }
    } else {
      return redirect()->route('user.paypal.cancel');
    }
  }

  public function paypalSuccess(Request $request)
  {
    $config = $this->paypalConfig();
    $paypalSetting = PaypalSetting::first();

    $provider = new PayPalClient($config);
    $provider->getAccessToken();

    $response = $provider->capturePaymentOrder($request->token);

    if (isset($response['status']) && $response['status'] === 'COMPLETED') {
      $total = getFinalPayableAmount();
      $paidAmount = round($total / $paypalSetting->currency_rate, 2);

      $this->storeOrder('paypal', 1, 'TRP-' . $response['id'], $paidAmount, 'USD');

      // Clear session
      $this->clearSession();

      return redirect()->route('user.payment.success');
    } else {
      return redirect()->route('user.paypal.cancel');
    }
  }

  public function paypalCancel()
  {
    toastr()->error('Thanh toán Paypal thất bại hoặc bị hủy! Vui lòng thử lại sau.');

    return redirect()->route('user.payment');
  }

  // VNPay
  public function payWithVNPay()
  {
    $vnpay = VnpaySetting::first();

    $vnp_TmnCode = $vnpay->tmn_code;
    $vnp_HashSecret = $vnpay->hash_secret;
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('user.vnpay.success');

    $txnRef = 'TRV-' . date('YmdHis') . '-' . Str::random(6);

    $orderAmount = getFinalPayableAmount();
    $vnp_Amount = $orderAmount * 100;

    $vnp_TxnRef = $txnRef;
    $vnp_OrderInfo = "Thanh toán đơn hàng #" . $txnRef;
    $vnp_OrderType = 'billpayment';
    $vnp_Locale = 'vn';
    $vnp_IpAddr = request()->ip();

    $inputData = [
      "vnp_Version" => "2.1.0",
      "vnp_TmnCode" => $vnp_TmnCode,
      "vnp_Amount" => $vnp_Amount,
      "vnp_Command" => "pay",
      "vnp_CreateDate" => date('YmdHis'),
      "vnp_CurrCode" => "VND",
      "vnp_IpAddr" => $vnp_IpAddr,
      "vnp_Locale" => $vnp_Locale,
      "vnp_OrderInfo" => $vnp_OrderInfo,
      "vnp_OrderType" => $vnp_OrderType,
      "vnp_ReturnUrl" => $vnp_Returnurl,
      "vnp_TxnRef" => $vnp_TxnRef,
    ];

    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
      if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
      } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
      }
      $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
      $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
      $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }

    return redirect()->away($vnp_Url);
  }

  public function vnpaySuccess(Request $request)
  {
    $vnpay = VnpaySetting::first();
    $vnp_HashSecret = $vnpay->hash_secret;

    $inputData = $request->all();
    $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';

    unset($inputData['vnp_SecureHashType'], $inputData['vnp_SecureHash']);

    ksort($inputData);
    $hashDataArr = [];
    foreach ($inputData as $key => $value) {
      $hashDataArr[] = urlencode($key) . '=' . urlencode($value);
    }
    $hashData = implode('&', $hashDataArr);

    $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    // dd([
    //   'my_hash' => $secureHash,
    //   'vnp_hash' => $vnp_SecureHash,
    //   'hashData' => $hashData
    // ]);

    if ($secureHash === $vnp_SecureHash) {
      $vnp_ResponseCode = $request->vnp_ResponseCode;
      $txnRef = $request->vnp_TxnRef;
      $paidAmount = $request->vnp_Amount / 100;

      if ($vnp_ResponseCode == '00') {
        $this->storeOrder('vnpay', 1, $txnRef, $paidAmount, 'VND');

        $this->clearSession();

        return redirect()->route('user.payment.success');
      }
    }

    return redirect()->route('user.vnpay.cancel');
  }

  public function vnpayCancel()
  {
    toastr()->error('Thanh toán VNPay thất bại hoặc bị hủy! Vui lòng thử lại sau.');

    return redirect()->route('user.payment');
  }

  public function payWithCod()
  {
    $this->storeOrder('cod', 0, 'TRC-' . date('YmdHis') . '-' . Str::random(6), getFinalPayableAmount(), 'VND');

    $this->clearSession();

    return redirect()->route('user.payment.success');
  }
}
