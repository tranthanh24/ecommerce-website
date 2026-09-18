<?php

namespace Tests\Feature;

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_settings_still_apply_in_console_processes(): void
    {
        DB::table('general_settings')->update(['time_zone' => 'Asia/Bangkok']);
        DB::table('email_configurations')->insert([
            'email' => 'mail@example.test', 'host' => 'smtp.example.test',
            'username' => 'test-user', 'password' => 'test-password',
            'port' => '587', 'encryption' => 'tls',
        ]);

        $this->app->getProvider(AppServiceProvider::class)->boot();

        $this->assertSame('Asia/Bangkok', config('app.timezone'));
        $this->assertSame('smtp.example.test', config('mail.mailers.smtp.host'));
        $this->assertSame('mail@example.test', config('mail.from.address'));
        $this->assertSame('test-key', config('broadcasting.connections.pusher.key'));
        $this->assertSame('test-secret', config('broadcasting.connections.pusher.secret'));
    }
}
