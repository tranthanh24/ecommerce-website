<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public static function roleDestinations(): array
    {
        return [
            'customer' => ['user', '/user/dashboard', '/user/profile'],
            'vendor' => ['vendor', '/vendor/dashboard', '/vendor/profile'],
            'admin' => ['admin', '/admin/dashboard', '/admin/profile'],
        ];
    }

    #[DataProvider('roleDestinations')]
    public function test_login_redirects_to_the_correct_dashboard(string $role, string $dashboard, string $profile): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect($dashboard);

        $this->assertAuthenticatedAs($user);
    }

    #[DataProvider('roleDestinations')]
    public function test_each_role_can_open_its_profile(string $role, string $dashboard, string $profile): void
    {
        $this->actingAs(User::factory()->create(['role' => $role]))
            ->get($profile)->assertOk();
    }

    public function test_customers_cannot_access_admin_or_vendor_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']));

        $this->get('/admin/settings')->assertRedirect('/user/dashboard');
        $this->get('/vendor/profile')->assertRedirect('/user/dashboard');
    }

    public function test_vendors_cannot_access_admin_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'vendor']))
            ->get('/admin/settings')->assertRedirect('/vendor/dashboard');
    }

    public function test_guests_cannot_access_protected_pages(): void
    {
        foreach (['/admin/settings', '/vendor/profile', '/user/profile'] as $uri) {
            $this->get($uri)->assertRedirect('/login');
        }
    }

    public function test_inactive_users_cannot_log_in(): void
    {
        $user = User::factory()->create(['status' => 'inactive']);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
