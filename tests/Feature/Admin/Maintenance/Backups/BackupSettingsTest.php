<?php

namespace Tests\Feature\Admin\Maintenance\Backups;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BackupSettingsTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.backups.settings.get'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.backups.settings.get'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.settings.get'));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->post(route('admin.backups.settings.set'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set_no_permission()
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.backups.settings.set'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => true,
            'password' => 'randomPassword',
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.backups.settings.set'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.backups.settings-successful'));
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.nightly_backup', 'value' => '1']);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.nightly_cleanup', 'value' => '1']);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.backup.encryption', 'value' => 'default']);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.backup.password', 'value' => $data['password']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'backup.notifications.mail.to', 'value' => $data['mail_to']]);
    }
}
