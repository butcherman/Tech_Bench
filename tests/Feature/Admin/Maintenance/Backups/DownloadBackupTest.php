<?php

namespace Tests\Feature\Admin\Maintenance\Backups;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadBackupTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->get(route('admin.backups.download', 'backup.zip'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->create())->get(route('admin.backups.download', 'backup.zip'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.download', 'backup.zip'));
        $response->assertDownload();
    }

    public function test_invoke_missing_file()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.download', 'backup2.zip'));
        $response->assertStatus(404);
    }
}
