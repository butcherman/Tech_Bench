<?php

namespace Tests\Feature\Admin\Backups;

use Tests\TestCase;
use App\Models\User;

class BackupSettingsTest extends TestCase
{
    /**
     * Invoke method
     */
    public function test_invoke_guest()
    {
        $data = [
            'enable'  => true,
            'email'   => 'testem@em.com',
            'password'=> 'somethingSafe',
        ];

        $response = $this->post(route('admin.backups.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'enable'  => true,
            'email'   => 'testem@em.com',
            'password'=> 'somethingSafe',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.backups.store'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $data = [
            'enable'  => true,
            'email'   => 'testem@em.com',
            'password'=> 'somethingSafe',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.backups.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.backup_settings_update'));
        $this->assertDatabaseHas('app_settings', ['key' => 'app.backups.enabled',          'value' => $data['enable']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.notifications.mail.to', 'value' => $data['email']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.backup.password',       'value' => $data['password']]);
    }

    public function test_invoke_remove_setting()
    {
        $data = [
            'enable'  => true,
            'email'   => null,
            'password'=> 'somethingSafe',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.backups.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.backup_settings_update'));
        $this->assertDatabaseHas('app_settings', ['key' => 'app.backups.enabled',          'value' => $data['enable']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.backup.password',       'value' => $data['password']]);
        $this->assertDatabaseMissing('app_settings', ['key' => 'backup.notifications.mail.to', 'value' => $data['email']]);
    }
}
