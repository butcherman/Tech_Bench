<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Tests\TestCase;

class SetEmailSettingsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'from_address' => 'test@test.com',
            'username' => 'someUsername',
            'password' => 'somePassword',
            'host' => 'smtp://somehost.blah',
            'port' => '2525',
            'encryption' => 'TLS',
            'requireAuth' => true,
        ];

        $response = $this->post(route('admin.set-email'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'from_address' => 'test@test.com',
            'username' => 'someUsername',
            'password' => 'somePassword',
            'host' => 'smtp://somehost.blah',
            'port' => '2525',
            'encryption' => 'TLS',
            'requireAuth' => true,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.set-email'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $data = [
            'from_address' => 'test@test.com',
            'username' => 'someUsername',
            'password' => 'somePassword',
            'host' => 'smtp://somehost.blah',
            'port' => '2525',
            'encryption' => 'TLS',
            'requireAuth' => true,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.set-email'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', ['key' => 'mail.from.address',                'value' => 'test@test.com']);
        $this->assertDatabaseHas('app_settings', ['key' => 'mail.mailers.smtp.username',       'value' => 'someUsername']);
        $this->assertDatabaseHas('app_settings', ['key' => 'mail.mailers.smtp.password',       'value' => 'somePassword']);
        $this->assertDatabaseHas('app_settings', ['key' => 'mail.mailers.smtp.host',           'value' => 'smtp://somehost.blah']);
        $this->assertDatabaseHas('app_settings', ['key' => 'mail.mailers.smtp.port',           'value' => '2525']);
        $this->assertDatabaseHas('app_settings', ['key' => 'mail.mailers.smtp.encryption',     'value' => 'TLS']);
    }
}
