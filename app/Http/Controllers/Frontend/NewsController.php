<?php

namespace App\Http\Controllers\Frontend;

use App\Models\News;
use App\Helper\MailHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionVerification;

class NewsController extends Controller
{
  public function newsSubscribe(Request $request)
  {
    $request->validate([
      'email' => ['required', 'email']
    ]);

    $subscriber = News::where('email', $request->email)->first();

    MailHelper::setMailConfig();
    Mail::purge();

    if ($subscriber) {
      if ($subscriber->is_verified === 0) {
        $subscriber->verified_token = Str::random(32);
        $subscriber->save();

        Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber));

        return response([
          'status' => 'success',
          'message' => 'Bạn đã đăng ký nhưng chưa xác nhận. Một email xác nhận mới đã được gửi lại.'
        ]);
      } else {
        return response(['status' => 'error', 'message' => 'Bạn đã đăng kí nhận bản tin rồi']);
      }
    }

    $subscriber = new News();
    $subscriber->email = $request->email;
    $subscriber->verified_token = Str::random(32);
    $subscriber->is_verified = 0;
    $subscriber->save();

    Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber));

    return response(['status' => 'success', 'message' => 'Một email xác nhận đã được gửi đến bạn. Vui lòng kiểm tra email của bạn để xác nhận đăng ký.']);
  }

  public function newsVerify($token)
  {
    $subscriber = News::where('verified_token', $token)->first();

    if ($subscriber) {
      $subscriber->verified_token = 'verified';
      $subscriber->is_verified = 1;
      $subscriber->save();

      toastr()->success('Cảm ơn bạn đã xác nhận đăng ký nhận bản tin');

      return redirect()->to('/');
    } else {
      toastr()->error('Mã xác nhận không hợp lệ hoặc đã hết hạn');

      return redirect()->to('/');
    }
  }
}
