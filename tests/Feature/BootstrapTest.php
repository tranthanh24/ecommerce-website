<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BootstrapTest extends TestCase
{
    public function test_artisan_can_boot_before_migrations(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        $this->assertFalse(Schema::hasTable('logo_settings'));

        $this->artisan('list')->assertSuccessful();
        $this->artisan('package:discover')->assertSuccessful();
    }

    public function test_all_migrations_run_on_an_empty_database(): void
    {
        $this->assertFalse(Schema::hasTable('users'));

        $this->artisan('migrate', ['--force' => true])->assertSuccessful();

        foreach (['users', 'products', 'orders', 'general_settings', 'logo_settings',
            'email_configurations', 'pusher_settings', 'chatbot_settings'] as $table) {
            $this->assertTrue(Schema::hasTable($table), $table);
        }
    }
}
