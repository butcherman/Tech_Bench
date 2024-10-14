<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('maint.backup.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('maint.backup.index'));
        $response->assertForbidden();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.backup.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        Queue::fake();

        $response = $this->post(route('maint.backup.store'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Queue::assertNothingPushed();
    }

    public function test_store_no_permission()
    {
        Queue::fake();

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('maint.backup.store'));
        $response->assertForbidden();

        Queue::assertNothingPushed();
    }

    public function test_store()
    {
        Queue::fake();

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->post(route('maint.backup.store'));
        $response->assertStatus(302);

        Artisan::shouldReceive('backup:run');
    }

    /**
     * show Method
     */
    public function test_show_guest()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->get(route('maint.backup.show', 'backup.zip'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('maint.backup.show', 'backup.zip'));
        $response->assertForbidden();
    }

    public function test_show()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.backup.show', 'backup.zip'));
        $response->assertDownload();
    }

    public function test_show_missing_file()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.backup.show', 'backup2.zip'));
        $response->assertStatus(404);
    }

    /**
     * destroy Method
     */
    public function test_destroy_guest()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->delete(route('maint.backup.destroy', 'backup.zip'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->createQuietly())
            ->delete(route('maint.backup.destroy', 'backup.zip'));
        $response->assertForbidden();

        Storage::disk('backups')->assertExists(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip');
    }

    public function test_destroy()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->delete(route('maint.backup.destroy', 'backup.zip'));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.backups.deleted'));

        Storage::disk('backups')->assertMissing(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip');
    }

    public function test_destroy_missing_file()
    {
        Storage::fake('backups');
        Storage::disk('backups')->put(config('backup.backup.name').
            DIRECTORY_SEPARATOR.'backup.zip', '123456');

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->delete(route('maint.backup.destroy', 'backup2.zip'));
        $response->assertStatus(404);
    }
}
