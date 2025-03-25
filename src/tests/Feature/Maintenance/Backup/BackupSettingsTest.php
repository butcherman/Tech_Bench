<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BackupSettingsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | show()
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $response = $this->get(route('maint.backups.settings.show'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.backups.settings.show'));

        $response->assertStatus(403);
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.backups.settings.show'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Maint/BackupSettings')
                ->has('nightly_backup')
                ->has('nightly_cleanup')
                ->has('encryption')
                ->has('password')
                ->has('mail_to'));
    }

    /*
    |---------------------------------------------------------------------------
    | update()
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->put(route('maint.backups.settings.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'nightly_backup' => true,
            'nightly_cleanup' => true,
            'encryption' => false,
            'password' => null,
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.backups.settings.update'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'nightly_backup' => false,
            'nightly_cleanup' => false,
            'encryption' => true,
            'password' => 'randomPassword',
            'mail_to' => 'randomDude@noemail.com',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.backups.settings.update'), $data);

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
        ])->assertDatabaseHas('app_settings', [
            'key' => 'backup.notifications.mail.to',
            'value' => $data['mail_to'],
        ]);
    }
}
