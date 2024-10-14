<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Tests\TestCase;

class BackupSettingsTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('maint.backups.settings.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('maint.backups.settings.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.backups.settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->put(route('maint.backups.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('maint.backups.settings.update'), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $data = [
            'nightly_backup' => false,
            'nightly_cleanup' => false,
            'encryption' => true,
            'password' => 'randomPassword',
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->put(route('maint.backups.settings.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.backups.settings-successful'));
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.nightly_backup',
            // 'value' => '1',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.nightly_cleanup',
            // 'value' => '1',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.backup.encryption',
            'value' => 'default',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.backup.password',
            'value' => $data['password'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.notifications.mail.to',
            'value' => $data['mail_to'],
        ]);
    }
}
