<?php

namespace Tests\Feature\Admin\Maintenance\Backups;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RunBackupTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Queue::fake();

        $response = $this->put(route('admin.backups.run'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Queue::assertNothingPushed();
    }

    public function test_invoke_no_permission()
    {
        Queue::fake();

        $response = $this->actingAs(User::factory()->create())->put(route('admin.backups.run'));
        $response->assertStatus(403);

        Queue::assertNothingPushed();
    }

    public function test_invoke()
    {
        Queue::fake();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.backups.run'));
        $response->assertSuccessful();

        Artisan::shouldReceive('backup:run');
    }
}
