<?php

namespace Tests\Feature\Admin;

use App\Jobs\ApplicationBackupJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.backups.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.backups.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.index'));
        $response->assertSuccessful();
    }

    /**
     * Store method
     */
    public function test_store_guest()
    {
        $data = [
            'enabled' => true,
            'number'  => 30,
        ];

        $response = $this->post(route('admin.backups.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'enabled' => true,
            'number'  => 30,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.backups.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'enabled' => true,
            'number'  => 30,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.backups.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', ['key' => 'app.backups.enabled', 'value' => true]);
        $this->assertDatabaseHas('app_settings', ['key' => 'app.backups.number',  'value' => 30]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.backups.show', ['run']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.backups.show', ['run']));
        $response->assertStatus(403);
    }

    public function test_show_bad_arg()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.show', ['bob']));
        $response->assertStatus(404);
    }

    public function test_show()
    {
        Bus::fake();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.show', ['run']));
        $response->assertStatus(302);
        Bus::assertDispatched(ApplicationBackupJob::class);
    }


    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->get(route('admin.backups.edit', 'randomFile.zip'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->actingAs(User::factory()->create())->get(route('admin.backups.edit', 'randomFile.zip'));
        $response->assertStatus(403);
    }

    public function test_edit_missing_file()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.edit', 'randomFile.zip'));
        $response->assertStatus(404);
    }

    public function test_edit()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.backups.edit', 'backup_file.zip'));
        $response->assertSuccessful();
        $response->assertDownload();
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->delete(route('admin.backups.destroy', 'backup_file.zip'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->actingAs(User::factory()->create())->delete(route('admin.backups.destroy', 'backup_file.zip'));
        $response->assertStatus(403);
    }

    public function test_destroy_bad_file()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.backups.destroy', 'backup.zip'));
        $response->assertStatus(404);
    }

    public function test_destroy()
    {
        $file = UploadedFile::fake()->create('backup_file.zip', 200);
        Storage::fake('backups');
        Storage::disk('backups')->putFileAs('', $file, 'backup_file.zip');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.backups.destroy', 'backup_file.zip'));
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'backup_file.zip deleted',
            'type'    => 'warning',
        ]);

        Storage::disk('backups')->assertMissing('backup_file.zip');
    }
}
