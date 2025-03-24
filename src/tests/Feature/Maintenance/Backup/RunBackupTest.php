<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RunBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        Queue::fake();

        $response = $this->get(route('maint.backups.run-backup'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        Queue::assertNothingPushed();
    }

    public function test_invoke_no_permission(): void
    {
        Queue::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.backups.run-backup'));

        $response->assertForbidden();

        Queue::assertNothingPushed();
    }

    public function test_invoke(): void
    {
        Queue::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.backups.run-backup'));

        $response->assertStatus(302);

        Artisan::shouldReceive('backup:run');
    }
}
