<?php

namespace Tests\Feature\_Console\Maint;

use App\Actions\Maintenance\RestoreBackup;
use App\Models\BackupRun;
use Mockery\MockInterface;
use Tests\TestCase;

class BackupRestoreCommandTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        BackupRun::factory()->create(['backup_name' => 'test_backup']);

        $this->mock(RestoreBackup::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')->once()->with('test_backup.zip');
        });

        $this->artisan('app:restore')
            ->expectsQuestion('Select Backup File to Restore', 'test_backup.zip')
            ->expectsConfirmation('Are you sure you want to continue?', 'yes')
            ->assertExitCode(0);
    }

    public function test_handle_canceled(): void
    {
        BackupRun::factory()->create(['backup_name' => 'test_backup']);

        $this->artisan('app:restore')
            ->expectsQuestion('Select Backup File to Restore', 'test_backup.zip')
            ->expectsConfirmation('Are you sure you want to continue?', 'no')
            ->assertExitCode(1);
    }
}
