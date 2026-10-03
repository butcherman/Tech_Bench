<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Jobs\Maintenance\RestoreBackupJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RestoreBackupControllerTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $data = [
            'selected' => 'test-backup',
        ];

        $response = $this->put(route('maint.backups.restore'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $data = [
            'selected' => 'test-backup',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.backups.restore'), $data);

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $data = [
            'selected' => 'test-backup',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.backups.restore'), $data);

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Maint/Backup/Restore')
            );

        Bus::assertDispatched(RestoreBackupJob::class);
    }
}
