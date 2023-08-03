<?php

namespace Tests\Feature\Admin\Maintenance\Backups;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteBackupTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->delete(route('admin.backups.destroy', 'backup.zip'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->create())->delete(route('admin.backups.destroy', 'backup.zip'));
        $response->assertStatus(403);

        Storage::disk('backups')->assertExists(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip');
    }

    public function test_invoke()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.backups.destroy', 'backup.zip'));
        $response->assertSuccessful();

        Storage::disk('backups')->assertMissing(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip');
    }

    public function test_invoke_missing_file()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.backups.destroy', 'backup2.zip'));
        $response->assertStatus(404);
    }
}
