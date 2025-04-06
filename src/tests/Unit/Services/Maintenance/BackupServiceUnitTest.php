<?php

namespace Tests\Unit\Services\Maintenance;

use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Services\Maintenance\BackupService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | deleteBackupFile()
    |---------------------------------------------------------------------------
    */
    public function test_delete_backup_file(): void
    {
        $backupName = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip';

        Storage::fake('backups');
        Storage::disk('backups')->put($backupName, '123456');

        $testObj = new BackupService;
        $testObj->deleteBackupFile('backup.zip');

        Storage::disk('backups')->assertMissing($backupName);
    }

    public function test_delete_backup_file_missing(): void
    {
        Storage::fake('backups');

        $this->expectException(BackupFileMissingException::class);

        $testObj = new BackupService;
        $testObj->deleteBackupFile('backup.zip');
    }

    /*
    |---------------------------------------------------------------------------
    | doesBackupExist()
    |---------------------------------------------------------------------------
    */
    public function test_does_backup_exist_true(): void
    {
        $backupName = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup.zip';

        Storage::fake('backups');
        Storage::disk('backups')->put($backupName, '123456');

        $testObj = new BackupService;
        $res = $testObj->doesBackupExist('backup.zip');

        $this->assertTrue($res);
    }

    public function test_does_backup_exist_false(): void
    {
        Storage::fake('backups');

        $testObj = new BackupService;
        $res = $testObj->doesBackupExist('backup.zip');

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | getBackupFileList()
    |---------------------------------------------------------------------------
    */
    public function test_get_backup_file_list(): void
    {
        $backup1 = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup1.zip';
        $backup2 = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup2.zip';
        $backup3 = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup3.zip';

        Storage::fake('backups');
        Storage::disk('backups')->put($backup1, '123456');
        Storage::disk('backups')->put($backup2, '123456');
        Storage::disk('backups')->put($backup3, '123456');

        $testObj = new BackupService;
        $res = $testObj->getBackupFileList();

        $this->assertCount(3, $res);
        $this->assertEquals($res, [$backup3, $backup2, $backup1]);
    }

    /*
    |---------------------------------------------------------------------------
    | getBackupListWithMetaData()
    |---------------------------------------------------------------------------
    */
    public function test_get_backup_list_with_meta_data(): void
    {
        $backup1 = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup1.zip';
        $backup2 = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup2.zip';
        $backup3 = config('backup.backup.name').DIRECTORY_SEPARATOR.'backup3.zip';

        Storage::fake('backups');
        Storage::disk('backups')->put($backup1, '123456');
        Storage::disk('backups')->put($backup2, '123456');
        Storage::disk('backups')->put($backup3, '123456');

        $testObj = new BackupService;
        $res = $testObj->getBackupListWithMetaData();

        $this->assertCount(3, $res);
        $this->assertArrayHasKey('name', $res[0]);
        $this->assertArrayHasKey('size', $res[0]);
        $this->assertArrayHasKey('modified', $res[0]);
    }

    /*
    |---------------------------------------------------------------------------
    | verifyBackupDiskSpace()
    |---------------------------------------------------------------------------
    */
    public function test_verify_backup_disk_space_true(): void
    {
        $stub = $this->createPartialMock(BackupService::class, [
            'getDiskFreeSpace',
            'getStorageDiskSize',
        ]);

        $stub->method('getDiskFreeSpace')->willReturn(5000);
        $stub->method('getStorageDiskSize')->willReturn(50);

        $res = $stub->verifyBackupDiskSpace();

        $this->assertTrue($res);
    }

    public function test_verify_backup_disk_space_false(): void
    {
        $stub = $this->createPartialMock(BackupService::class, [
            'getDiskFreeSpace',
            'getStorageDiskSize',
        ]);

        $stub->method('getDiskFreeSpace')->willReturn(500);
        $stub->method('getStorageDiskSize')->willReturn(500);

        $res = $stub->verifyBackupDiskSpace();

        $this->assertFalse($res);
    }
}
