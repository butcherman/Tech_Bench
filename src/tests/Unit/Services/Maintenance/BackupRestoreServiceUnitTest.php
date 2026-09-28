<?php

namespace Tests\Unit\Services\Maintenance;

use App\Contracts\DatabaseRestoreContract;
use App\DTO\Maintenance\RestoreTransaction;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Services\Maintenance\BackupRestoreService;
use App\Services\Maintenance\BackupService;
use App\Services\Maintenance\MySqlRestoreProcess;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;
use ZanySoft\Zip\Zip;

class BackupRestoreServiceUnitTest extends TestCase
{
    /** @var BackupService&Mock */
    protected BackupService $backupService;

    /** @var ConsoleOutputService&Mock */
    protected ConsoleOutputService $output;

    protected BackupRestoreService $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->backupService = Mockery::mock(BackupService::class);
        $this->output = Mockery::mock(ConsoleOutputService::class);

        $this->testObj = new BackupRestoreService(
            $this->backupService,
            $this->output,
            new MySqlRestoreProcess
        );
    }

    /*
    |---------------------------------------------------------------------------
    | prepareRestore()
    |---------------------------------------------------------------------------
    */
    public function test_prepare_restore_missing_backup(): void
    {
        Exceptions::fake();

        $backupName = 'test-backup.zip';

        $this->backupService
            ->shouldReceive('ensureExists')
            ->once()
            ->with($backupName)
            ->andThrow(BackupFileMissingException::class);

        $this->expectException(BackupFileMissingException::class);

        $this->testObj->prepareRestore($backupName);

        Exceptions::assertReported(BackupFileMissingException::class);
    }

    public function test_prepare_restore(): void
    {
        $this->createTestBackup();
        $archive = 'test_backup.zip';
        $path = storage_path('framework/testing/restore');

        $reflection = new \ReflectionClass($this->testObj);
        $prop = $reflection->getProperty('tmpPath');
        $prop->setValue($this->testObj, $path);

        $this->backupService
            ->shouldReceive('ensureExists')
            ->twice()
            ->with($archive);
        $this->backupService
            ->shouldReceive('path')
            ->once()
            ->with($archive)
            ->andReturn($path.'/'.$archive);

        $this->output->shouldReceive('writeLn')->times(3);

        $result = $this->testObj->prepareRestore($archive);

        $this->assertInstanceOf(RestoreTransaction::class, $result);
    }

    /*
    |---------------------------------------------------------------------------
    | mountArchive()
    |---------------------------------------------------------------------------
    */
    public function test_mount_archive(): void
    {
        $this->createTestBackup();
        $archive = 'test_backup.zip';
        $path = storage_path('framework/testing/restore');

        $this->backupService
            ->shouldReceive('ensureExists')
            ->once()
            ->with($archive);
        $this->backupService
            ->shouldReceive('path')
            ->once()
            ->with($archive)
            ->andReturn($path.'/'.$archive);

        $result = $this->testObj->mountArchive($archive);

        $this->assertInstanceOf(Zip::class, $result);
    }

    public function test_mount_archive_missing_file(): void
    {
        Exceptions::fake();

        $archive = 'test_backup.zip';

        $this->backupService
            ->shouldReceive('ensureExists')
            ->once()
            ->with($archive)
            ->andThrow(BackupFileMissingException::class);

        $this->expectException(BackupFileMissingException::class);

        $result = $this->testObj->mountArchive($archive);

        $this->assertNull($result);

        Exceptions::assertReported(BackupFileMissingException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | restoreDatabase()
    |---------------------------------------------------------------------------
    */
    public function test_restore_database(): void
    {
        $dbFile = 'test_backup/database.sql';

        $process = Mockery::mock(DatabaseRestoreContract::class);

        $process->shouldReceive('restore')
            ->once()
            ->with($dbFile);

        $testObj = new BackupRestoreService(
            $this->backupService,
            $this->output, $process
        );
        $testObj->restoreDatabase($dbFile);
    }

    /*
    |---------------------------------------------------------------------------
    | restoreFileSystem()
    |---------------------------------------------------------------------------
    */
    public function test_restore_file_system(): void
    {
        $transaction = new RestoreTransaction(
            id: '1234',
            backupName: 'test.zip',
            path: 'restore',
        );

        $extractedPath = $transaction->extractedPath();
        $storagePath = storage_path('app');
        $envPath = App::environmentFilePath();

        File::shouldReceive('isDirectory')
            ->with($extractedPath.'/app')
            ->andReturnFalse();
        File::shouldReceive('isDirectory')
            ->with($extractedPath.'/var/www/html')
            ->andReturn($extractedPath.'/var/www/html');
        File::shouldReceive('deleteDirectory')->with($storagePath);

        File::shouldReceive('copyDirectory')
            ->with($extractedPath.'/var/www/html/storage/app', $storagePath);

        File::shouldReceive('delete')->with($envPath);
        File::shouldReceive('move')
            ->with($extractedPath.'/var/www/html/.env', $envPath);

        $res = $this->testObj->restoreFileSystem($transaction);
        $this->assertTrue($res);
    }

    public function test_restore_file_system_ver8_and_lower(): void
    {
        $transaction = new RestoreTransaction(
            id: '1234',
            backupName: 'test.zip',
            path: 'restore',
        );

        $extractedPath = $transaction->extractedPath();
        $storagePath = storage_path('app');
        $envPath = App::environmentFilePath();

        File::shouldReceive('isDirectory')
            ->with($extractedPath.'/app')
            ->andReturn($extractedPath.'/app');
        File::shouldReceive('deleteDirectory')->with($storagePath);

        File::shouldReceive('copyDirectory')
            ->with($extractedPath.'/app/storage/app', $storagePath);

        File::shouldReceive('delete')->with($envPath);
        File::shouldReceive('move')->with($extractedPath.'/app/.env', $envPath);

        $res = $this->testObj->restoreFileSystem($transaction);
        $this->assertTrue($res);
    }

    public function test_restore_file_system_invalid_filesystem(): void
    {
        Exceptions::fake();

        $transaction = new RestoreTransaction(
            id: '1234',
            backupName: 'test.zip',
            path: 'restore',
        );

        $extractedPath = $transaction->extractedPath();
        $storagePath = storage_path('app');
        $envPath = App::environmentFilePath();

        File::shouldReceive('isDirectory')
            ->with($extractedPath.'/app')
            ->andReturnFalse();
        File::shouldReceive('isDirectory')
            ->with($extractedPath.'/var/www/html')
            ->andReturnFalse();
        File::shouldNotReceive('deleteDirectory')->with($storagePath);

        File::shouldNotReceive('copyDirectory')
            ->with($extractedPath.'/app/storage/app', $storagePath);

        File::shouldNotReceive('delete')->with($envPath);
        File::shouldNotReceive('move')
            ->with($extractedPath.'/app/.env', $envPath);

        $this->expectException(BackupFileInvalidException::class);

        $res = $this->testObj->restoreFileSystem($transaction);
        $this->assertNull($res);

        Exceptions::assertReported(BackupFileInvalidException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | validateBackupStructure()
    |---------------------------------------------------------------------------
    */
    public function test_validate_backup_structure_missing_database_dump(): void
    {
        $zip = Mockery::mock(Zip::class);

        $zip->shouldReceive('has')
            ->with('app/.env')
            ->andReturn(true);

        $zip->shouldReceive('has')
            ->with('app/keystore/version')
            ->andReturn(true);

        $zip->shouldReceive('has')
            ->with('app/storage/app/.gitignore')
            ->andReturn(true);

        $zip->shouldReceive('has')
            ->with('app/storage/logs/.gitignore')
            ->andReturn(true);

        $zip->shouldReceive('has')
            ->with('var/www/html/.env')
            ->andReturn(false);

        $zip->shouldReceive('has')
            ->with('var/www/html/keystore/version')
            ->andReturn(false);

        $zip->shouldReceive('has')
            ->with('var/www/html/storage/app/.gitignore')
            ->andReturn(false);

        $zip->shouldReceive('has')
            ->with('var/www/html/storage/logs/.gitignore')
            ->andReturn(false);

        $zip->shouldReceive('has')
            ->with('db-dumps/mysql-tech-bench.sql')
            ->andReturn(false);

        $zip->shouldReceive('close');

        $this->setArchive($zip);

        $this->expectException(BackupFileInvalidException::class);

        $this->testObj->validateBackupStructure();
    }

    public function test_validate_backup_structure_missing_file(): void
    {
        $zip = Mockery::mock(Zip::class);

        $requiredFiles = [
            '.env',
            'keystore/version',
            'storage/app/.gitignore',
            'storage/logs/.gitignore',
        ];

        foreach ($requiredFiles as $file) {
            $zip->shouldReceive('has')
                ->with('app/'.$file)
                ->andReturn(false);

            $zip->shouldReceive('has')
                ->with('var/www/html/'.$file)
                ->andReturn(false);
        }

        $zip->shouldReceive('close');

        $this->setArchive($zip);

        $this->expectException(BackupFileInvalidException::class);

        $this->testObj->validateBackupStructure();
    }

    /*
    |---------------------------------------------------------------------------
    | Helpers
    |---------------------------------------------------------------------------
    */
    protected function createTestBackup(): Zip
    {
        $path = storage_path('framework/testing/restore');
        File::ensureDirectoryExists($path);

        $zip = new Zip;

        $zip->create($path.'/test_backup.zip');
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

    private function setArchive(Zip $archive): void
    {
        $reflection = new \ReflectionClass($this->testObj);

        $property = $reflection->getProperty('archive');
        $property->setValue($this->testObj, $archive);
    }

    // public static function requiredBackupFilesProvider(): array
    // {
    //     return [
    //         'environment' => ['.env'],
    //         'version' => ['keystore/version'],
    //         'storage gitignore' => ['storage/app/.gitignore'],
    //         'logs gitignore' => ['storage/logs/.gitignore'],
    //     ];
    // }
}
