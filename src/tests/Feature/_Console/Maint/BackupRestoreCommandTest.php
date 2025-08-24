<?php

namespace Tests\Feature\_Console\Maint;

use App\Services\Maintenance\BackupRestoreService;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;
use ZanySoft\Zip\Zip;

class BackupRestoreCommandTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_canceled(): void
    {
        $this->createTestBackup();

        $this->artisan('app:backup-restore')
            ->expectsQuestion('Select Backup File to Restore', 'test_backup.zip')
            ->expectsOutput('Backup File is Valid')
            ->expectsConfirmation('Restore Environment File?', 'yes')
            ->expectsConfirmation('Restore Log Files?', 'yes')
            ->expectsConfirmation('Restore SSL Certificate?', 'yes')
            ->expectsConfirmation('Continue?', 'no')
            ->assertFailed();
    }

    public function test_handle(): void
    {
        Process::fake([
            'docker ps' => Process::result(true)
        ]);

        $this->createTestBackup();

        $this->mock(BackupRestoreService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBackupListWithMetaData')
                ->once()
                ->andReturn(['name' => 'test_backup.zip']);
            $mock->shouldReceive('validateBackupArchive')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('extractBackup')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('restoreDatabase')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('restoreFileSystem')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('restoreEnvironmentFile')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('restoreLogFiles')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('restoreCert')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('deleteExtractedFiles')
                ->once()
                ->andReturn(true);
        });

        $this->artisan('app:backup-restore')
            ->expectsQuestion('Select Backup File to Restore', 'test_backup.zip')
            ->expectsOutput('Backup File is Valid')
            ->expectsConfirmation('Restore Environment File?', 'yes')
            ->expectsConfirmation('Restore Log Files?', 'yes')
            ->expectsConfirmation('Restore SSL Certificate?', 'yes')
            ->expectsConfirmation('Continue?', 'yes')
            ->assertExitCode(0);
    }

    /*
    |---------------------------------------------------------------------------
    | Create a test backup file to use in the restore testing.
    |---------------------------------------------------------------------------
    */
    protected function createTestBackup(): Zip
    {
        Storage::disk('backups')->makeDirectory('tech-bench');

        $zip = new Zip;

        $zip->create(Storage::disk('backups')->path('tech-bench') . '/test_backup.zip');
        $zip->addFromString('db-dumps/mysql-tech-bench.sql', 'test sql file');
        $zip->addFromString('app/.env', 'test env file');
        $zip->addFromString('app/keystore/version', '7.0.0');
        $zip->addFromString('app/keystore/server.crt', 'test ssl cert');
        $zip->addFromString('app/keystore/private/server.key', 'test key file');
        $zip->addFromString('app/storage/app/.gitignore', 'test file');
        $zip->addFromString('app/storage/logs/.gitignore', 'test file');
        $zip->close();

        return $zip;
    }
}
