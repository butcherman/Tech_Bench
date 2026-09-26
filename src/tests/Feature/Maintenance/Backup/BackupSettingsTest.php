<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Tests\TestCase;

class BackupSettingsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
        ];

        $response = $this->put(route('maint.backups.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.backups.update'), $data);

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'nightly_backup' => ! config('backup.nightly_backup'),
            'nightly_cleanup' => ! config('backup.nightly_cleanup'),
            'encryption' => ! config('backup.backup.encryption'),
            'password' => 'randomPassword',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.backups.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(
                'success',
                __('admin.backups.settings-successful')
            );

        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.nightly_backup',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'backup.nightly_cleanup',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'backup.backup.encryption',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'backup.backup.password',
            'value' => $data['password'],
        ]);
    }
}
