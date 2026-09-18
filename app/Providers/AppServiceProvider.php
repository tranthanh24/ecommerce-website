<?php

namespace App\Providers;

use App\Models\Coupon;
use App\Models\LogoSetting;
use App\Models\PusherSetting;
use App\Models\GeneralSetting;
use App\Models\EmailConfiguration;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrapFive();

    // Artisan must be able to boot before the first migration has run.
    $hasTable = fn (string $table): bool => !$this->app->runningInConsole() || Schema::hasTable($table);
    $logoSetting = $hasTable('logo_settings') ? LogoSetting::first() : null;
    $generalSetting = $hasTable('general_settings') ? GeneralSetting::first() : null;
    $mailSetting = $hasTable('email_configurations') ? EmailConfiguration::first() : null;
    $pusherSetting = $hasTable('pusher_settings') ? PusherSetting::first() : null;

    $coupon = $hasTable('coupons') ? Coupon::where('status', 1)->first() : null;

    // Set the default timezone
    $timezone = config('app.timezone');
    if ($generalSetting && is_string($generalSetting->time_zone) && $generalSetting->time_zone !== '') {
      $timezone = $generalSetting->time_zone;
    }
    Config::set('app.timezone', $timezone);

    // Set mail config
    if ($mailSetting) {
      Config::set('mail.mailers.smtp.host', $mailSetting->host);
      Config::set('mail.mailers.smtp.port', $mailSetting->port);
      Config::set('mail.mailers.smtp.encryption', $mailSetting->encryption);
      Config::set('mail.mailers.smtp.username', $mailSetting->username);
      Config::set('mail.mailers.smtp.password', $mailSetting->password);
      Config::set('mail.from.address', $mailSetting->email);
    }

    // Set pusher config
    if ($pusherSetting) {
      Config::set('broadcasting.connections.pusher.key', $pusherSetting->key);
      Config::set('broadcasting.connections.pusher.secret', $pusherSetting->secret);
      Config::set('broadcasting.connections.pusher.app_id', $pusherSetting->app_id);
      Config::set('broadcasting.connections.pusher.options.cluster', $pusherSetting->cluster);
      Config::set('broadcasting.connections.pusher.options.useTLS', true);
    }

    // Share variables with all views
    View::composer('*', function ($view) use ($generalSetting, $logoSetting, $pusherSetting, $coupon) {
      $view->with([
        'logo' => $logoSetting,
        'pusher' => $pusherSetting,
        'setting' => $generalSetting,
        'coupon' => $coupon
      ]);
    });
  }
}
