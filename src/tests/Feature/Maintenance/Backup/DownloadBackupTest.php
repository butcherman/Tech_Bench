<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\BackupRun;
use App\Models\User;
use App\Services\Maintenance\BackupService;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class DownloadBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $run = BackupRun::factory()->create();

        $response = $this->get(
            route('maint.backups.download', $run->backup_name)
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
            ->get(route('maint.backups.download', $run->backup_name));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $run = BackupRun::factory()->create();
        $stream = $this->mock(StreamedResponse::class);

        $this->mock(BackupService::class, function (MockInterface $mock) use ($stream) {
            $mock->shouldReceive('download')->once()->andReturn($stream);
        });

        $this->actingAs($user)
            ->get(route('maint.backups.download', $run->backup_name));
    }
}
