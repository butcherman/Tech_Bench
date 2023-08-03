<?php

namespace Tests\Feature\Admin\Maintenance\Backups;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FetchBackupsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup1.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup2.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup3.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup4.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup5.zip', '123456');

        $response = $this->get(route('admin.backups.fetch'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup1.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup2.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup3.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup4.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup5.zip', '123456');

        $response = $this->actingAs(User::factory()->create())->get(route('admin.backups.fetch'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup1.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup2.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup3.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup4.zip', '123456');
        Storage::disk('backups')->put(config('backup.backup.name').DIRECTORY_SEPARATOR.'backup5.zip', '123456');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.fetch'));
        $response->assertSuccessful();
        $response->assertJson(['backup5.zip', 'backup4.zip', 'backup3.zip', 'backup2.zip', 'backup1.zip']);
    }
}
