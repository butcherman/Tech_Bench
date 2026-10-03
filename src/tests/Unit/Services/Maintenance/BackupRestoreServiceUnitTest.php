<?php

namespace Tests\Unit\Services\Maintenance;

use App\Contracts\DatabaseRestoreContract;
use App\DTO\Maintenance\RestoreTransaction;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Exceptions\Maintenance\RestoreFailedException;
use App\Services\Maintenance\BackupRestoreService;
use App\Services\Maintenance\BackupService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Mockery;
use Mockery\Mock;
use PragmaRX\Version\Package\Version;
use Tests\TestCase;
use ZanySoft\Zip\Zip;

class BackupRestoreServiceUnitTest extends TestCase
{
    /** @var BackupService&Mock */
    private BackupService $backupService;

    /** @var ConsoleOutputService&Mock */
    private ConsoleOutputService $output;

    /** @var DatabaseRestoreContract&Mock */
    private DatabaseRestoreContract $databaseRestore;

    /** @var BackupRestoreService&Mock */
    private BackupRestoreService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupService = Mockery::mock(BackupService::class);
        $this->output = Mockery::mock(ConsoleOutputService::class);
        $this->databaseRestore = Mockery::mock(
            DatabaseRestoreContract::class
        );

        $this->service = new BackupRestoreService(
            $this->backupService,
            $this->output,
            $this->databaseRestore,
        );
    }

    /*
    |---------------------------------------------------------------------------
    | mountArchive()
    |---------------------------------------------------------------------------
    */
    public function test_mount_archive(): void
    {
        $archive = 'test_back';

        $this->createTestBackup($archive);

        $this->backupService->shouldReceive('ensureExists')->once();
        $this->backupService
            ->shouldReceive('path')
            ->once()
            ->andReturn(storage_path('framework/testing/backups/tech-bench').'/'.$archive.'.zip');

        $res = $this->service->mountArchive($archive);

        $this->assertInstanceOf(Zip::class, $res);
    }

    public function test_mount_archive_missing_file(): void
    {
        Exceptions::fake();

        $archive = 'test_back';

        $this->backupService
            ->shouldReceive('ensureExists')
            ->once()
            ->andThrow(BackupFileMissingException::class);

        $this->expectException(BackupFileMissingException::class);

        $res = $this->service->mountArchive($archive);
        $this->assertNull($res);

        Exceptions::assertReported(BackupFileMissingException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | restoreDatabase()
    |---------------------------------------------------------------------------
    */
    public function test_restore_database(): void
    {
        $databaseBackup = '/tmp/restore/database.sql';

        $this->databaseRestore
            ->shouldReceive('restore')
            ->once()
            ->with($databaseBackup);

        $this->service->restoreDatabase($databaseBackup);
    }

    /*
    |---------------------------------------------------------------------------
    | restoreFileSystem()
    |---------------------------------------------------------------------------
    */
    public function test_restore_file_system(): void
    {
        $transaction = $this->transaction();
        $transactionPath = $transaction->extractedPath();

        File::shouldReceive('isDirectory')
            ->once()
            ->with($transactionPath.'/app')
            ->andReturnFalse();
        File::shouldReceive('isDirectory')
            ->once()
            ->with($transactionPath.'/var/www/html')
            ->andReturn($transactionPath.'/var/www/html');

        File::shouldReceive('deleteDirectory')
            ->once()
            ->with(storage_path('app'));
        File::shouldReceive('copyDirectory')
            ->once();

        File::shouldReceive('delete')->once()->with(App::environmentFilePath());
        File::shouldReceive('move')->once();

        $this->service->restoreFileSystem($transaction);

        $this->assertTrue(true);
    }

    /*
    |---------------------------------------------------------------------------
    | validateBackupStructure()
    |---------------------------------------------------------------------------
    */
    public function test_validate_backup_structure(): void
    {
        $zip = Mockery::mock(Zip::class);
        $structureFiles = [
            '.env',
            'keystore/version',
            'storage/app/.gitignore',
            'storage/logs/.gitignore',
        ];

        foreach ($structureFiles as $file) {
            $zip->shouldReceive('has')
                ->once()
                ->with('app/'.$file)
                ->andReturn(false);

            $zip->shouldReceive('has')
                ->once()
                ->with('var/www/html/'.$file)
                ->andReturn(true);
        }

        $zip->shouldReceive('has')
            ->once()
            ->with('db-dumps/mysql-tech-bench.sql')
            ->andReturn(true);

        $this->setArchive($zip);

        $this->service->validateBackupStructure();

        $this->assertTrue(true);
    }

    public function test_validate_backup_structure_older_layout(): void
    {
        $zip = Mockery::mock(Zip::class);
        $structureFiles = [
            '.env',
            'keystore/version',
            'storage/app/.gitignore',
            'storage/logs/.gitignore',
        ];

        foreach ($structureFiles as $file) {
            $zip->shouldReceive('has')
                ->once()
                ->with('app/'.$file)
                ->andReturn(true);
        }

        $zip->shouldReceive('has')
            ->once()
            ->with('db-dumps/mysql-tech-bench.sql')
            ->andReturn(true);

        $this->setArchive($zip);

        $this->service->validateBackupStructure();

        $this->assertTrue(true);
    }

    public function test_validate_backup_structure_missing_file(): void
    {
        Exceptions::fake();

        $zip = Mockery::mock(Zip::class);
        $missing = 'storage/logs/.gitignore';
        $structureFiles = [
            '.env',
            'keystore/version',
            'storage/app/.gitignore',
            'storage/logs/.gitignore',
        ];

        foreach ($structureFiles as $file) {
            $zip->shouldReceive('has')
                ->once()
                ->with('app/'.$file)
                ->andReturn(false);

            $zip->shouldReceive('has')
                ->once()
                ->with('var/www/html/'.$file)
                ->andReturn($file !== $missing);
        }

        $zip->shouldReceive('close')->once();

        $this->setArchive($zip);

        $this->expectException(BackupFileInvalidException::class);

        $this->service->validateBackupStructure();

        Exceptions::assertReported(BackupFileInvalidException::class);
    }

    public function test_validate_backup_structure_missing_db_backup(): void
    {
        Exceptions::fake();

        $zip = Mockery::mock(Zip::class);
        $structureFiles = [
            '.env',
            'keystore/version',
            'storage/app/.gitignore',
            'storage/logs/.gitignore',
        ];

        foreach ($structureFiles as $file) {
            $zip->shouldReceive('has')
                ->once()
                ->with('app/'.$file)
                ->andReturn(true);
        }

        $zip->shouldReceive('has')
            ->once()
            ->with('db-dumps/mysql-tech-bench.sql')
            ->andReturn(false);

        $this->setArchive($zip);

        $this->expectException(BackupFileInvalidException::class);

        $this->service->validateBackupStructure();

        Exceptions::assertReported(BackupFileInvalidException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Text
    |---------------------------------------------------------------------------
    */
    public function test_validate_extracted_backup(): void
    {
        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists($path.'/var/www/html/storage/app');
        File::ensureDirectoryExists($path.'/db-dumps');

        File::put($path.'/db-dumps/mysql-tech-bench.sql', '-- database');

        File::put($path.'/var/www/html/.env', 'APP_ENV=testing');

        $method = $this->getPrivateMethod('validateExtractedBackup');
        $method->invoke($this->service, $transaction);

        $this->assertTrue(true);

        File::deleteDirectory($transaction->path);
    }

    public function test_validate_extracted_backup_missing_database(): void
    {
        Exceptions::fake();

        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists($path.'/var/www/html/storage/app');

        File::put($path.'/var/www/html/.env', 'APP_ENV=testing');

        $this->expectException(BackupFileInvalidException::class);

        $method = $this->getPrivateMethod('validateExtractedBackup');
        $method->invoke($this->service, $transaction);

        Exceptions::assertReported(BackupFileInvalidException::class);

        File::deleteDirectory($transaction->path);
    }

    public function test_validate_extracted_backup_missing_storage_path(): void
    {
        Exceptions::fake();

        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists($path.'/db-dumps');
        File::deleteDirectory($path.'/storage/app');

        File::put($path.'/db-dumps/mysql-tech-bench.sql', '-- database');

        $this->expectException(BackupFileInvalidException::class);

        $method = $this->getPrivateMethod('validateExtractedBackup');
        $method->invoke($this->service, $transaction);

        File::deleteDirectory($transaction->path);
        Exceptions::assertReported(BackupFileInvalidException::class);
    }

    public function test_validate_extracted_backup_missing_env_file(): void
    {
        Exceptions::fake();

        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists($path.'/var/www/html/storage/app');
        File::ensureDirectoryExists($path.'/db-dumps');

        File::put($path.'/db-dumps/mysql-tech-bench.sql', '-- database');

        $this->expectException(BackupFileInvalidException::class);

        $method = $this->getPrivateMethod('validateExtractedBackup');
        $method->invoke($this->service, $transaction);

        Exceptions::assertReported(BackupFileInvalidException::class);

        File::deleteDirectory($transaction->path);
    }

    /*
    |---------------------------------------------------------------------------
    | validateBackupVersion()
    |---------------------------------------------------------------------------
    */
    public function test_validate_backup_version_accepts_older_version(): void
    {
        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists(
            $path.'/app/keystore'
        );

        File::put(
            $path.'/app/keystore/version',
            "0.0.1 test\n"
        );

        $zip = Mockery::mock(Zip::class);

        $zip->shouldReceive('has')
            ->once()
            ->with('app/keystore/version')
            ->andReturn(true);

        $zip->shouldReceive('extract')
            ->once()
            ->with(
                $path,
                ['app/keystore/version']
            );

        $this->setArchive($zip);

        $method = $this->getPrivateMethod('validateBackupVersion');

        $method->invoke($this->service, $path);

        File::deleteDirectory($transaction->path);

        $this->assertTrue(true);
    }

    public function test_validate_backup_version_accepts_current_version(): void
    {
        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists(
            $path.'/app/keystore'
        );

        $currentVersion = (new Version)
            ->compact();

        File::put(
            $path.'/app/keystore/version',
            $currentVersion.' test'
        );

        $zip = Mockery::mock(Zip::class);

        $zip->shouldReceive('has')
            ->once()
            ->with('app/keystore/version')
            ->andReturn(true);

        $zip->shouldReceive('extract')
            ->once()
            ->with(
                $path,
                ['app/keystore/version']
            );

        $this->setArchive($zip);

        $method = $this->getPrivateMethod('validateBackupVersion');

        $method->invoke($this->service, $path);

        File::deleteDirectory($transaction->path);

        $this->assertTrue(true);
    }

    public function test_validate_backup_version_rejects_newer_version(): void
    {
        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists(
            $path.'/app/keystore'
        );

        File::put(
            $path.'/app/keystore/version',
            "999.999.999 test\n"
        );

        $zip = Mockery::mock(Zip::class);

        $zip->shouldReceive('has')
            ->once()
            ->with('app/keystore/version')
            ->andReturn(true);

        $zip->shouldReceive('extract')
            ->once()
            ->with(
                $path,
                ['app/keystore/version']
            );

        $this->setArchive($zip);

        $method = $this->getPrivateMethod('validateBackupVersion');

        $this->expectException(BackupFileInvalidException::class);

        try {
            $method->invoke($this->service, $path);
        } finally {
            File::deleteDirectory($transaction->path);
        }
    }

    public function test_validate_backup_version_supports_v9_layout(): void
    {
        $transaction = $this->transaction();

        $path = $this->createExtractedDirectory($transaction);

        File::ensureDirectoryExists(
            $path.'/var/www/html/keystore'
        );

        File::put(
            $path.'/var/www/html/keystore/version',
            "0.0.1 test\n"
        );

        $zip = Mockery::mock(Zip::class);

        $zip->shouldReceive('has')
            ->once()
            ->with('app/keystore/version')
            ->andReturn(false);

        $zip->shouldReceive('extract')
            ->once()
            ->with(
                $path,
                ['var/www/html/keystore/version']
            );

        $this->setArchive($zip);

        $method = $this->getPrivateMethod('validateBackupVersion');

        $method->invoke($this->service, $path);

        File::deleteDirectory($transaction->path);

        $this->assertTrue(true);
    }

    /*
    |---------------------------------------------------------------------------
    | verifyRestore()
    |---------------------------------------------------------------------------
    */
    public function test_verify_restore_succeeds(): void
    {
        $baseTables = [
            'backup_runs',
            'app_settings',
            'customers',
            'users',
        ];

        File::shouldReceive('isDirectory')
            ->once()
            ->with(storage_path('app/private'))
            ->andReturn(true);

        File::shouldReceive('isDirectory')
            ->once()
            ->with(storage_path('app/public'))
            ->andReturn(true);

        foreach ($baseTables as $table) {
            $query = Mockery::mock();

            $query->shouldReceive('exists')
                ->once()
                ->andReturn(true);

            DB::shouldReceive('table')
                ->once()
                ->with($table)
                ->andReturn($query);
        }

        $this->service->verifyRestore();

        $this->assertTrue(true);
    }

    public function test_verify_restore_rejects_missing_private_directory(): void
    {
        Exceptions::fake();

        File::shouldReceive('isDirectory')
            ->once()
            ->with(storage_path('app/private'))
            ->andReturn(false);

        $this->expectException(RestoreFailedException::class);

        $this->service->verifyRestore();

        Exceptions::assertReported(RestoreFailedException::class);
    }

    public function test_verify_restore_rejects_missing_table(): void
    {
        $table = 'backup_runs';

        File::shouldReceive('isDirectory')
            ->with(storage_path('app/private'))
            ->andReturn(true);

        File::shouldReceive('isDirectory')
            ->with(storage_path('app/public'))
            ->andReturn(true);

        $query = Mockery::mock();

        $query->shouldReceive('exists')
            ->once()
            ->andReturn(false);

        DB::shouldReceive('table')
            ->once()
            ->with($table)
            ->andReturn($query);

        $this->expectException(RestoreFailedException::class);

        $this->service->verifyRestore();
    }

    /*
    |---------------------------------------------------------------------------
    | createRollbackSnapshot()
    |---------------------------------------------------------------------------
    */

    public function test_create_rollback_snapshot_creates_database_snapshot(): void
    {
        $transaction = $this->transaction();

        Process::fake([
            '*' => Process::result(
                output: 'CREATE TABLE test ();',
                errorOutput: '',
                exitCode: 0,
            ),
        ]);

        File::shouldReceive('put')
            ->once();

        File::shouldReceive('copyDirectory')
            ->once()
            ->with(
                storage_path('app'),
                $transaction->rollbackStoragePath(),
            );

        App::shouldReceive('environmentFilePath')
            ->once()
            ->andReturn('/var/www/html/.env');

        File::shouldReceive('copy')
            ->once()
            ->with(
                '/var/www/html/.env',
                $transaction->rollbackPath().'/.env',
            );

        $this->service->createRollbackSnapshot($transaction);

        Process::assertRan(function ($process) {
            return $process->command[0] === 'mysqldump';
        });
    }

    public function test_create_rollback_snapshot_throws_when_database_dump_fails(): void
    {
        Exceptions::fake();

        $transaction = $this->transaction();

        Process::fake([
            '*' => Process::result(
                output: '',
                errorOutput: 'mysqldump failed',
                exitCode: 1,
            ),
        ]);

        $this->expectException(RestoreFailedException::class);

        $this->service->createRollbackSnapshot($transaction);

        Exceptions::assertReported(RestoreFailedException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | rollback()
    |---------------------------------------------------------------------------
    */

    public function test_rollback_restores_storage_and_database(): void
    {
        $transaction = $this->transaction();

        File::shouldReceive('copyDirectory')
            ->once()
            ->with(
                $transaction->rollbackStoragePath(),
                storage_path('app'),
            );

        $this->databaseRestore
            ->shouldReceive('restore')
            ->once()
            ->with(
                $transaction->rollbackDatabasePath()
            );

        $this->service->rollback($transaction);
    }

    public function test_rollback_restores_database_after_storage_is_restored(): void
    {
        $transaction = $this->transaction();

        File::shouldReceive('copyDirectory')
            ->once()
            ->with(
                $transaction->rollbackStoragePath(),
                storage_path('app'),
            );

        $this->databaseRestore
            ->shouldReceive('restore')
            ->once()
            ->with(
                $transaction->rollbackDatabasePath()
            );

        $this->service->rollback($transaction);
    }

    /*
    |---------------------------------------------------------------------------
    | cleanupTransaction()
    |---------------------------------------------------------------------------
    */
    public function test_cleanup_transaction_deletes_transaction_directory(): void
    {
        $transaction = $this->transaction();

        File::shouldReceive('deleteDirectories')
            ->once()
            ->with($transaction->path);

        $this->service->cleanupTransaction($transaction);

        $this->assertTrue(true);
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
            backupName: 'TechBench-2026-09-27.zip',
            path: '/tmp/restore/20260927-173300-abcde',
        );
    }

    private function setArchive(Zip $archive): void
    {
        $reflection = new \ReflectionClass($this->service);

        $property = $reflection->getProperty('archive');
        $property->setValue($this->service, $archive);
    }

    private function getPrivateMethod(string $method)
    {
        $reflection = new \ReflectionClass($this->service);

        $method = $reflection->getMethod($method);

        return $method;
    }

    protected function createTestBackup(string $archiveName): Zip
    {
        $path = storage_path('framework/testing/backups/tech-bench');

        File::ensureDirectoryExists($path);

        $zip = new Zip;

        $zip->create($path.DIRECTORY_SEPARATOR.$archiveName.'.zip');
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

    private function createExtractedDirectory(RestoreTransaction $transaction): string
    {
        $path = $transaction->extractedPath();

        File::deleteDirectory($transaction->path);

        File::ensureDirectoryExists($path);

        return $path;
    }
}
