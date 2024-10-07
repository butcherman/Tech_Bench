<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Tests\TestCase;

class EmailSettingsTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.email-settings.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.email-settings.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.email-settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'from_address' => 'new@email.org',
            'username' => 'testName',
            'password' => 'blahBlah',
            'host' => 'randomHost.com',
            'port' => 25,
            'encryption' => 'none',
            'require_aut' => true,
        ];

        $response = $this->put(route('admin.email-settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $data = [
            'from_address' => 'new@email.org',
            'username' => 'testName',
            'password' => 'blahBlah',
            'host' => 'randomHost.com',
            'port' => 25,
            'encryption' => 'none',
            'require_aut' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.email-settings.update'), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'from_address' => 'new@email.org',
            'username' => 'testName',
            'password' => 'blahBlah',
            'host' => 'randomHost.com',
            'port' => 25,
            'encryption' => 'none',
            'require_auth' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.email-settings.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.email.updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.from.address',
            'value' => $data['from_address'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.username',
            'value' => $data['username'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.password',
            'value' => $data['password'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.host',
            'value' => $data['host'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.port',
            'value' => $data['port'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.encryption',
            'value' => $data['encryption'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.require_auth',
        ]);
    }
}
