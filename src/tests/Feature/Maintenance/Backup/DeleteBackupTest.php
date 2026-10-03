<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\BackupRun;
use App\Models\User;
use App\Services\Maintenance\BackupService;
use Mockery\MockInterface;
use Tests\TestCase;

class DeleteBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $run = BackupRun::factory()->create();

        $response = $this->delete(
            route('maint.backups.delete', $run->backup_name)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $run = BackupRun::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('maint.backups.delete', $run->backup_name));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $run = BackupRun::factory()->create();

        $this->mock(BackupService::class, function (MockInterface $mock) {
            $mock->shouldReceive('delete')->once(); // ->with($run);
        });

        $response = $this->actingAs($user)
            ->delete(route('maint.backups.delete', $run->backup_name));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.backups.deleted'));
    }
}
