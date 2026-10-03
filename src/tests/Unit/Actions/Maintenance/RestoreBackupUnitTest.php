<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\RestoreBackup;
use App\DTO\Maintenance\RestoreTransaction;
use App\Exceptions\Maintenance\RestoreFailedException;
use App\Services\Maintenance\BackupRestoreService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Exceptions;
use Mockery\MockInterface;
use Tests\TestCase;

class RestoreBackupUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        $archive = 'test-backup';
        $transaction = $this->transaction();

        $output = $this->mock(
            ConsoleOutputService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('writeLn')->times(9);
            });

        Artisan::shouldReceive('call')->once()->with('down');
        Artisan::shouldReceive('call')->once()->with('migrate --force');
        Artisan::shouldReceive('call')->once()->with('up');

        $service = $this->mock(BackupRestoreService::class);
        $service->shouldReceive('prepareRestore')->once()
            ->with($archive)
            ->andReturn($transaction);
        $service->shouldReceive('createRollbackSnapshot')
            ->once()
            ->with($transaction);
        $service->shouldReceive('restoreDatabase')
            ->once()
            ->with($transaction->extractedPath().'/db-dumps/mysql-tech-bench.sql');
        $service->shouldReceive('restoreFileSystem')
            ->once()
            ->with($transaction);
        $service->shouldReceive('verifyRestore')->once();
        $service->shouldReceive('cleanupTransaction')
            ->once()
            ->with($transaction);

        $testObj = new RestoreBackup($service, $output);
        $testObj->__invoke($archive);
    }

    public function test_invoke_failed_restore(): void
    {
        Exceptions::fake();

        $archive = 'test-backup';
        $transaction = $this->transaction();

        $output = $this->mock(
            ConsoleOutputService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('writeLn')->times(5);
            });

        Artisan::shouldReceive('call')->once()->with('down');
        Artisan::shouldReceive('call')->once()->with('up');

        $service = $this->mock(BackupRestoreService::class);
        $service->shouldReceive('prepareRestore')->once()
            ->with($archive)
            ->andReturn($transaction);
        $service->shouldReceive('createRollbackSnapshot')
            ->once()
            ->with($transaction)
            ->andThrow(RestoreFailedException::class);
        $service->shouldReceive('rollback')->once()->with($transaction);

        $this->expectException(RestoreFailedException::class);

        $testObj = new RestoreBackup($service, $output);
        $testObj->__invoke($archive);

        Exceptions::assertReported(RestoreFailedException::class);
    }

    public function test_invoke_failed_rollback(): void
    {
        Exceptions::fake();

        $archive = 'test-backup';
        $transaction = $this->transaction();

        $output = $this->mock(
            ConsoleOutputService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('writeLn')->times(5);
            });

        Artisan::shouldReceive('call')->once()->with('down');

        $service = $this->mock(BackupRestoreService::class);
        $service->shouldReceive('prepareRestore')->once()
            ->with($archive)
            ->andReturn($transaction);
        $service->shouldReceive('createRollbackSnapshot')
            ->once()
            ->with($transaction)
            ->andThrow(RestoreFailedException::class);
        $service->shouldReceive('rollback')
            ->once()
            ->with($transaction)
            ->andThrow(RestoreFailedException::class);

        $this->expectException(RestoreFailedException::class);

        $testObj = new RestoreBackup($service, $output);
        $testObj->__invoke($archive);

        Exceptions::assertReported(RestoreFailedException::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function transaction(): RestoreTransaction
    {
        return new RestoreTransaction(
            id: '20260927-173300-abcde',
            backupName: 'test-backup.zip',
            path: '/tmp/restore/20260927-173300-abcde',
        );
    }
}
