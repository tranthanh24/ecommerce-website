<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LogoSetting;
use App\Models\PusherSetting;
use App\Models\ChatbotSetting;
use App\Models\GeneralSetting;
use App\Models\EmailConfiguration;
use App\Services\Chatbot\ChatbotSettings;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;

class SettingController extends Controller
{
  use ImageUpload;

  public function index()
  {
    $generalSetting = GeneralSetting::first();
    $emailSetting = EmailConfiguration::first();
    $pusherSetting = PusherSetting::first();
    $logo = LogoSetting::first();
    $chatbotSetting = ChatbotSetting::first();
    $chatbotHasGeminiKey = $chatbotSetting?->getRawOriginal('gemini_api_key') ?? false;
    $chatbotHasOpenAiKey = $chatbotSetting?->getRawOriginal('openai_api_key') ?? false;

    return view('admin.setting.index', compact(
      'logo',
      'emailSetting',
      'pusherSetting',
      'generalSetting',
      'chatbotSetting',
      'chatbotHasGeminiKey',
      'chatbotHasOpenAiKey'
    ));
  }

  public function generalSettingUpdate(Request $request)
  {
    $request->validate([
      'site_name' => ['required', 'string', 'max:255'],
      'contact_email' => ['required', 'email', 'max:100'],
      'contact_phone' => ['required', 'max:20'],
      'contact_address' => ['required', 'max:255'],
      'map' => ['required', 'url'],
      'currency_icon' => ['required', 'string', 'max:10'],
      'time_zone' => ['required']
    ]);

    GeneralSetting::updateOrCreate(
      ['id' => 1],
      [
        'site_name' => $request->site_name,
        'contact_email' => $request->contact_email,
        'contact_phone' => $request->contact_phone,
        'contact_address' => $request->contact_address,
        'map' => $request->map,
        'currency_icon' => $request->currency_icon,
        'time_zone' => $request->time_zone,
      ]
    );

    toastr()->success('Cài đặt chung đã được cập nhật thành công');

    return redirect()->back();
  }

  public function emailSettingUpdate(Request $request)
  {
    $request->validate([
      'email' => ['required', 'email'],
      'host' => ['required', 'string', 'max:100'],
      'username' => ['required', 'string', 'max:100'],
      'password' => ['required', 'string', 'max:100'],
      'port' => ['required', 'string', 'max:100'],
      'encryption' => ['required', 'string', 'max:100'],
    ]);

    EmailConfiguration::updateOrCreate(
      ['id' => 1],
      [
        'email' => $request->email,
        'host' => $request->host,
        'username' => $request->username,
        'password' => $request->password,
        'port' => $request->port,
        'encryption' => $request->encryption,
      ]
    );

    toastr()->success('Cài đặt email đã được cập nhật thành công');

    return redirect()->back();
  }

  public function logoUpdate(Request $request)
  {
    $request->validate([
      'logo' => ['nullable', 'image', 'max:2048'],
      'favicon' => ['nullable', 'image', 'max:2048'],
      'footer' => ['required', 'string', 'max:500'],
    ]);

    $logoSetting = LogoSetting::first() ?? new LogoSetting();

    $oldLogo = $logoSetting->logo ?? null;
    $oldFavicon = $logoSetting->favicon ?? null;

    $logo = $this->updateImage($request, 'logo', 'uploads/logo', $oldLogo);
    $favicon = $this->updateImage($request, 'favicon', 'uploads/logo', $oldFavicon);

    $logoSetting->logo = $logo;
    $logoSetting->favicon = $favicon;
    $logoSetting->footer = $request->footer;
    $logoSetting->save();

    toastr()->success('Logo và Footer đã được cập nhật thành công');

    return redirect()->back();
  }

  public function pusherSettingUpdate(Request $request)
  {
    $request->validate([
      'app_id' => ['required', 'string'],
      'key' => ['required', 'string'],
      'secret' => ['required', 'string'],
      'cluster' => ['required', 'string'],
    ]);

    PusherSetting::updateOrCreate(
      ['id' => 1],
      [
        'app_id' => $request->app_id,
        'key' => $request->key,
        'secret' => $request->secret,
        'cluster' => $request->cluster,
      ]
    );

    toastr()->success('Cài đặt Pusher đã được cập nhật thành công');

    return redirect()->back();
  }

  public function chatbotSettingUpdate(Request $request, ChatbotSettings $chatbotSettings)
  {
    $request->validate([
      'enabled' => ['nullable', 'boolean'],
      'provider' => ['required', 'string', 'in:gemini,openai'],
      'log_to_db' => ['nullable', 'boolean'],
      'max_history_messages' => ['required', 'integer', 'min:1', 'max:50'],
      'max_product_results' => ['required', 'integer', 'min:10', 'max:50'],

      'gemini_base_url' => ['required', 'string', 'max:255'],
      'gemini_model' => ['required', 'string', 'max:100'],
      'gemini_temperature' => ['required', 'numeric', 'min:0', 'max:2'],
      'gemini_timeout' => ['required', 'integer', 'min:5', 'max:120'],
      'gemini_max_output_tokens' => ['required', 'integer', 'min:50', 'max:4000'],
      'gemini_top_p' => ['required', 'numeric', 'min:0', 'max:1'],
      'gemini_top_k' => ['required', 'integer', 'min:0', 'max:100'],
      'gemini_api_key' => ['nullable', 'string', 'max:500'],

      'openai_base_url' => ['required', 'string', 'max:255'],
      'openai_model' => ['required', 'string', 'max:100'],
      'openai_temperature' => ['required', 'numeric', 'min:0', 'max:2'],
      'openai_timeout' => ['required', 'integer', 'min:5', 'max:120'],
      'openai_max_tokens' => ['required', 'integer', 'min:50', 'max:4000'],
      'openai_api_key' => ['nullable', 'string', 'max:500'],
    ]);

    $setting = ChatbotSetting::first() ?? new ChatbotSetting();

    $setting->enabled = $request->boolean('enabled');
    $setting->provider = $request->provider;
    $setting->log_to_db = $request->boolean('log_to_db');
    $setting->max_history_messages = $request->max_history_messages;
    $setting->max_product_results = $request->max_product_results;

    $setting->gemini_base_url = $request->gemini_base_url;
    $setting->gemini_model = $request->gemini_model;
    $setting->gemini_temperature = $request->gemini_temperature;
    $setting->gemini_timeout = $request->gemini_timeout;
    $setting->gemini_max_output_tokens = $request->gemini_max_output_tokens;
    $setting->gemini_top_p = $request->gemini_top_p;
    $setting->gemini_top_k = $request->gemini_top_k;
    if ($request->filled('gemini_api_key')) {
      $setting->gemini_api_key = $request->gemini_api_key;
    }

    $setting->openai_base_url = $request->openai_base_url;
    $setting->openai_model = $request->openai_model;
    $setting->openai_temperature = $request->openai_temperature;
    $setting->openai_timeout = $request->openai_timeout;
    $setting->openai_max_tokens = $request->openai_max_tokens;
    if ($request->filled('openai_api_key')) {
      $setting->openai_api_key = $request->openai_api_key;
    }

    $setting->save();
    $chatbotSettings->clearCache();

    toastr()->success('Cấu hình chatbot đã được cập nhật');

    return redirect()->back();
  }
}
