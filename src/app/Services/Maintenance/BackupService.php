<?php

namespace App\Services\Maintenance;

use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Services\_Base\FileMaintenanceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupService extends FileMaintenanceService
{
    /**
     * Storage object used to interact with the Storage Backup Disk.
     *
     * @var Storage
     */
    protected $storage;

    /**
     * Base name of the backup file.
     *
     * @var string
     */
    protected $backupBaseName;

    public function __construct()
    {
        $this->storage = Storage::disk('backups');
        $this->backupBaseName = config('backup.backup.name') . DIRECTORY_SEPARATOR;
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackupFile(string $backupName): void
    {
        if (! $this->doesBackupExist($backupName)) {
            throw new BackupFileMissingException($backupName);
        }

        $this->storage->delete($this->backupBaseName . $backupName);
    }

    /**
     * Determine if a given name belongs to a backup file.
     */
    public function doesBackupExist(string $backupName): bool
    {
        return $this->storage->exists($this->backupBaseName . $backupName);
    }

    /**
     * Get a list of all backup files in the Backup Disk.
     */
    public function getBackupFileList(): array
    {
        return array_reverse($this->storage->files('tech-bench'));
    }

    /**
     * Get a list of backup files with size and timestamp.
     */
    public function getBackupListWithMetaData(): array
    {
        $fileList = $this->getBackupFileList();
        $metaList = [];

        foreach ($fileList as $fileName) {
            $metaList[] = [
                'name' => str_replace($this->backupBaseName, '', $fileName),
                'size' => $this->storage->size($fileName),
                'modified' => $this->storage->lastModified($fileName),
            ];
        }

        return $metaList;
    }

    /**
     * Check if the backup directory has enough free space to store a system
     * backup.
     */
    public function verifyBackupDiskSpace(): bool
    {
        $backupFreeSpace = $this->getDiskFreeSpace('backups');
        $appStorageSize = $this->getStorageDiskSize('local');
        $logStorageSize = $this->getStorageDiskSize('logs');

        $estimatedBackupSize = $appStorageSize + $logStorageSize;

        $checkPassed = $backupFreeSpace > $estimatedBackupSize;

        Log::debug('Checking disk for available space in backup drive.', [
            'backup_free_space' => $this->readableFileSize($backupFreeSpace),
            'estimated_backup_size' => $this->readableFileSize($estimatedBackupSize),
            'check_passed' => $this->readableFileSize($checkPassed) ? 'Yes' : 'No',
        ]);

        return $checkPassed;
    }
}
