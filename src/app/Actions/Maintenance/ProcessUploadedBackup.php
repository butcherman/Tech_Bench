<?php

namespace App\Actions\Maintenance;

use App\Enums\DiskEnum;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Models\BackupRun;
use App\Services\File\TusUploadService;
use App\Services\Maintenance\BackupRestoreService;
use App\Traits\HandleFileTrait;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedBackup
{
    use HandleFileTrait;

    /**
     * Create a new class instance.
     */
    public function __construct(
        protected TusUploadService $uploadSvc,
        protected BackupRestoreService $svc
    ) {}

    /**
     * Validate and save a manually uploaded backup file
     */
    public function __invoke(string $uploadId): void
    {
        $upload = $this->uploadSvc->getCompletedUpload($uploadId);

        $this->uploadSvc
            ->validateMimeType($upload, [
                'application/x-zip-compressed',
                'application/zip',
            ]);

        $baseName = $upload->metadata['name'];
        $fileName = $this->validateFileName($baseName);
        $filePath = config('backup.backup.name').DIRECTORY_SEPARATOR.$fileName;

        $this->uploadSvc->finalizeUpload($upload, DiskEnum::backups, $filePath);

        $archive = $this->svc->mountArchive($fileName);

        try {
            $this->svc->validateBackupStructure($archive);
        } catch (BackupFileInvalidException $e) {
            Storage::disk('backups')->delete($filePath);

            throw $e;
        }

        BackupRun::create([
            'backup_name' => $fileName,
            'type' => 'upload',
            'status' => 'completed',
            'started_at' => null,
            'completed_at' => null,
            'size' => $upload->metadata['size'],
        ]);
    }

    private function validateFileName(string $baseName): string
    {
        $baseName = $this->cleanFilename($baseName);

        return $this->checkForDuplicate(
            DiskEnum::backups,
            config('backup.backup.name'),
            $baseName
        );
    }
}
