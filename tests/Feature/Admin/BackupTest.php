<?php

namespace Tests\Feature\Admin;

use App\Jobs\ApplicationBackupJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
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

    //  TODO - Test Download and destroy methods
}
