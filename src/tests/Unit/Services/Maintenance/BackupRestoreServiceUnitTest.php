<?php

namespace Tests\Unit\Services\Maintenance;

use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Exceptions\Maintenance\RestoreFailedException;
use App\Services\Maintenance\BackupRestoreService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use ReflectionClass;
use Tests\TestCase;
use ZanySoft\Zip\Zip;

class BackupRestoreServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | deleteExtractedFiles()
    |---------------------------------------------------------------------------
    */
    public function test_delete_extracted_files(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->makeDirectory('tech-bench');
        Storage::disk('backups')->put('tech-bench/restore-tmp', 'testZip', true);

        $testObj = new BackupRestoreService;
        $testObj->deleteExtractedFiles();

        Storage::assertMissing('restore-tmp');
    }

    /*
    |---------------------------------------------------------------------------
    | extractBackup()
    |---------------------------------------------------------------------------
    */
    public function test_extract_backup(): void
    {
        Storage::fake('backups');

        $this->createTestBackup();

        $class = new BackupRestoreService;
        $reflectedClass = new ReflectionClass(new BackupRestoreService);
        $mountMethod = $reflectedClass->getMethod('mountArchive');
        $mountMethod->setAccessible(true);

        $mountMethod->invoke($class, 'test_backup.zip');

        $res = $class->extractBackup();
        $this->assertTrue($res);
    }

    public function test_extract_backup_invalid(): void
    {
        Storage::fake('backups');

        $this->expectException(BackupFileMissingException::class);

        $testObj = new BackupRestoreService;
        $testObj->extractBackup();
    }

    /*
    |---------------------------------------------------------------------------
    | restoreCert()
    |---------------------------------------------------------------------------
    */
    public function test_restore_cert(): void
    {
        $restorePath = 'restore-tmp/app/keystore';

        Storage::fake('backups');
        Storage::disk('backups')->makeDirectory($restorePath);

        /** @var BackupRestoreService */
        $stub = $this->partialMock(
            BackupRestoreService::class,
            function (MockInterface $mock) use ($restorePath) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->expects('wipeDirectory')
                    ->with(base_path('keystore'))
                    ->andReturn(null);
                $mock->expects('restoreFiles')
                    ->with(
                        base_path('keystore'),
                        Storage::disk('backups')
                            ->path($restorePath)
                    )
                    ->andReturn(null);
            }
        );

        $stub->__construct();
        $stub->restoreCert();
    }

    /*
    |---------------------------------------------------------------------------
    | restoreDatabase()
    |---------------------------------------------------------------------------
    */
    public function test_restore_database(): void
    {
        $testQuery = 'INSERT INTO `app_settings` VALUES (99, "test.key", '
            . json_encode("123") . ', NOW(), NOW())';

        /** @var BackupRestoreService */
        $stub = $this->partialMock(
            BackupRestoreService::class,
            function (MockInterface $mock) use ($testQuery) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->expects('wipeDatabase')->andReturn(null);
                $mock->expects('getDbFile')->andReturns([$testQuery, "\n"]);
            }
        );

        $res = $stub->restoreDatabase();
        $this->assertTrue($res);

        Artisan::shouldReceive('migrate --force');

        $this->assertDatabaseHas('app_settings', [
            'id' => 99,
            'key' => 'test.key',
        ]);
    }

    public function test_restore_database_fail(): void
    {
        $testQuery = 'INSERT INTO `app_settings` VALUES (99)';

        /** @var BackupRestoreService */
        $stub = $this->partialMock(
            BackupRestoreService::class,
            function (MockInterface $mock) use ($testQuery) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->expects('wipeDatabase')->andReturn(null);
                $mock->expects('getDbFile')->andReturns([$testQuery, "\n"]);
            }
        );

        $this->expectException(RestoreFailedException::class);

        $stub->restoreDatabase();
    }

    /*
    |---------------------------------------------------------------------------
    | restoreEnvironmentFile()
    |---------------------------------------------------------------------------
    */
    public function test_restore_environment_file(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->makeDirectory('restore-tmp/app');
        Storage::disk('backups')->put('restore-tmp/app/.env', 'test env file');

        App::useEnvironmentPath(Storage::disk('backups')->path(''));

        $testObj = new BackupRestoreService;
        $testObj->restoreEnvironmentFile();

        Storage::disk('backups')->assertExists('.env.testing');
    }

    /*
    |---------------------------------------------------------------------------
    | restoreLogFiles()
    |---------------------------------------------------------------------------
    */
    public function test_restore_log_files(): void
    {
        $restorePath = 'restore-tmp/app/storage/logs';

        Storage::fake('backups');
        Storage::disk('backups')->makeDirectory($restorePath);

        /** @var BackupRestoreService */
        $stub = $this->partialMock(
            BackupRestoreService::class,
            function (MockInterface $mock) use ($restorePath) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->expects('wipeDirectory')
                    ->with(storage_path('logs'))
                    ->andReturn(null);
                $mock->expects('restoreFiles')
                    ->with(
                        storage_path('logs'),
                        Storage::disk('backups')
                            ->path($restorePath)
                    )
                    ->andReturn(null);
            }
        );

        $stub->__construct();
        $stub->restoreLogFiles();
    }

    /*
    |---------------------------------------------------------------------------
    | restoreFileSystem()
    |---------------------------------------------------------------------------
    */
    public function test_restore_file_system(): void
    {
        $restorePath = 'restore-tmp/app/storage/app';

        Storage::fake('backups');
        Storage::disk('backups')->makeDirectory($restorePath);


        /** @var BackupRestoreService */
        $stub = $this->partialMock(
            BackupRestoreService::class,
            function (MockInterface $mock) use ($restorePath) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->expects('wipeDirectory')
                    ->with(storage_path('app'))
                    ->andReturn(null);
                $mock->expects('restoreFiles')
                    ->with(
                        storage_path('app'),
                        Storage::disk('backups')
                            ->path($restorePath)
                    )
                    ->andReturn(null);
            }
        );

        $stub->__construct();
        $stub->restoreFileSystem();
    }

    /*
    |---------------------------------------------------------------------------
    | validateBackupArchive()
    |---------------------------------------------------------------------------
    */
    public function test_validate_backup_archive(): void
    {
        Storage::fake('backups');

        $this->createTestBackup();

        $testObj = new BackupRestoreService;
        $res = $testObj->validateBackupArchive('test_backup.zip');

        $this->assertTrue($res);
    }

    public function test_validate_backup_file_missing(): void
    {
        Storage::fake('backups');

        $this->expectException(BackupFileMissingException::class);

        $testObj = new BackupRestoreService;
        $testObj->validateBackupArchive('test_backup.zip');
    }

    public function test_validate_backup_file_invalid(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->put('tech-bench/test_backup.zip', 'not a zip');

        $this->expectException(BackupFileInvalidException::class);

        $testObj = new BackupRestoreService;
        $testObj->validateBackupArchive('test_backup.zip');
    }

    public function test_validate_backup_file_invalid_structure(): void
    {
        Storage::fake('backups');

        $zip = $this->createTestBackup();
        $zip->open(Storage::disk('backups')->path('tech-bench/test_backup.zip'));
        $zip->delete('app/.env');
        $zip->close();

        $this->expectException(BackupFileInvalidException::class);

        $testObj = new BackupRestoreService;
        $testObj->validateBackupArchive('test_backup.zip');
    }

    public function test_validate_backup_file_invalid_version(): void
    {
        Storage::fake('backups');

        $zip = $this->createTestBackup();
        $zip->open(Storage::disk('backups')->path('tech-bench/test_backup.zip'));
        $zip->delete('app/keystore/version');
        $zip->addFromString('app/keystore/version', '99.0.0');
        $zip->close();

        $this->expectException(BackupFileInvalidException::class);

        $testObj = new BackupRestoreService;
        $testObj->validateBackupArchive('test_backup.zip');
    }

    /*
    |---------------------------------------------------------------------------
    | restoreFiles()
    |---------------------------------------------------------------------------
    */
    public function test_restore_files(): void
    {
        Storage::fake('backups');
        Storage::disk('backups')->makeDirectory('from_directory/test/');
        Storage::disk('backups')->makeDirectory('to_directory');
        Storage::disk('backups')->put('from_directory/test/test.txt', 'test file to move');

        $basePath = Storage::disk('backups')->path('');

        $stub = new class extends BackupRestoreService
        {
            public function __invoke($to, $from)
            {
                $this->restoreFiles($to, $from);
            }
        };

        $stub($basePath . '/to_directory', $basePath . '/from_directory');

        Storage::disk('backups')->assertExists('to_directory/test/test.txt');
    }

    /*
    |---------------------------------------------------------------------------
    | Create a test backup file to use in the restore testing.
    |---------------------------------------------------------------------------
    */
    protected function createTestBackup(): Zip
    {
        Storage::disk('backups')->makeDirectory('tech-bench');

        $zip = new Zip();

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
