<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Providers\AppServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Supply only storefront settings, never a copy of production data.
        if (Schema::hasTable('general_settings')) {
            DB::table('general_settings')->insert([
                'site_name' => 'Test Store',
                'contact_email' => 'store@example.test',
                'contact_phone' => '0123456789',
                'contact_address' => 'Test address',
                'map' => '',
                'currency_icon' => 'đ',
                'time_zone' => 'Asia/Ho_Chi_Minh',
            ]);
            DB::table('logo_settings')->insert([
                'logo' => 'frontend/images/avatar.jpg',
                'favicon' => 'frontend/images/avatar.jpg',
                'footer' => 'Test Store',
            ]);
            DB::table('pusher_settings')->insert([
                'app_id' => 'test-app',
                'key' => 'test-key',
                'secret' => 'test-secret',
                'cluster' => 'ap1',
            ]);

            // Refresh settings captured before RefreshDatabase ran migrations.
            $this->app->getProvider(AppServiceProvider::class)->boot();
        }
    }
}
