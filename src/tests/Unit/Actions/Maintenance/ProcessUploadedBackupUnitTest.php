<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\ProcessUploadedBackup;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Services\File\TusUploadService;
use App\Services\Maintenance\BackupRestoreService;
use ArthurPatriot\Tus\Helpers\TusFile;
use Illuminate\Support\Facades\Exceptions;
use Mockery\MockInterface;
use Tests\TestCase;
use ZanySoft\Zip\Zip;

class ProcessUploadedBackupUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        $tusFile = new TusFile(
            id: 'test-backup',
            path: 'backup.zip',
            metadata: [
                'name' => 'backup.zip',
                'purpose' => 'backup',
                'extension' => 'zip',
                'size' => 6,
            ],
        );

        $zip = $this->mock(Zip::class);

        $restoreSvc = $this->mock(BackupRestoreService::class, function (MockInterface $mock) use ($zip) {
            $mock->shouldReceive('mountArchive')->once()->with('backup.zip')->andReturn($zip);
            $mock->shouldReceive('validateBackupStructure')->once()->with($zip);
        });

        $uploadSvc = $this->mock(TusUploadService::class, function (MockInterface $mock) use ($tusFile) {
            $mock->shouldReceive('getCompletedUPload')->once()->andReturn($tusFile);
            $mock->shouldReceive('validateMimeType')->once()->andReturn(true);
            $mock->shouldReceive('finalizeUpload')->once();
        });

        $testObj = new ProcessUploadedBackup($uploadSvc, $restoreSvc);
        $testObj($tusFile->id);

        $this->assertDatabaseHas('backup_runs', [
            'backup_name' => 'backup.zip',
            'status' => 'completed',
        ]);
    }

    public function test_invoke_invalid_backup(): void
    {
        Exceptions::fake();

        $tusFile = new TusFile(
            id: 'test-backup',
            path: 'backup.zip',
            metadata: [
                'name' => 'backup.zip',
                'purpose' => 'backup',
                'extension' => 'zip',
                'size' => 6,
            ],
        );

        $zip = $this->mock(Zip::class);

        $restoreSvc = $this->mock(BackupRestoreService::class, function (MockInterface $mock) use ($zip) {
            $mock->shouldReceive('mountArchive')->once()->with('backup.zip')->andReturn($zip);
            $mock->shouldReceive('validateBackupStructure')->once()->with($zip)->andThrow(BackupFileInvalidException::class);
        });

        $uploadSvc = $this->mock(TusUploadService::class, function (MockInterface $mock) use ($tusFile) {
            $mock->shouldReceive('getCompletedUPload')->once()->andReturn($tusFile);
            $mock->shouldReceive('validateMimeType')->once()->andReturn(true);
            $mock->shouldReceive('finalizeUpload')->once();
        });

        $this->expectException(BackupFileInvalidException::class);

        $testObj = new ProcessUploadedBackup($uploadSvc, $restoreSvc);
        $testObj($tusFile->id);

        Exceptions::assertReported(BackupFileInvalidException::class);
    }
}
