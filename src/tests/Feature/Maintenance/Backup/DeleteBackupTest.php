<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name') .
            DIRECTORY_SEPARATOR . 'backup.zip', '123456');

        $response = $this->delete(route('maint.backups.delete', 'backup.zip'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name') .
            DIRECTORY_SEPARATOR . 'backup.zip', '123456');

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('maint.backups.delete', 'backup.zip'));

        $response->assertForbidden();

        Storage::disk('backups')->assertExists(config('backup.backup.name') .
            DIRECTORY_SEPARATOR . 'backup.zip');
    }

    public function test_invoke(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name') .
            DIRECTORY_SEPARATOR . 'backup.zip', '123456');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->delete(route('maint.backups.delete', 'backup.zip'));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.backups.deleted'));

        Storage::disk('backups')->assertMissing(config('backup.backup.name') .
            DIRECTORY_SEPARATOR . 'backup.zip');
    }

    public function test_invoke_missing_file(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name') .
            DIRECTORY_SEPARATOR . 'backup.zip', '123456');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->delete(route('maint.backups.delete', 'backup2.zip'));

        $response->assertStatus(404);
    }
}
