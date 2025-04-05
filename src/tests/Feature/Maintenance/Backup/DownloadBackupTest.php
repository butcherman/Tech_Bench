<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->get(route('maint.backups.download', 'backup.zip'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.backups.download', 'backup.zip'));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.backups.download', 'backup.zip'));

        $response->assertDownload();
    }

    public function test_invoke_missing_file(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.backups.download', 'backup2.zip'));

        $response->assertStatus(404);
    }
}
