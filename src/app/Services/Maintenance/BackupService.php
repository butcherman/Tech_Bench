<?php

namespace App\Services\Maintenance;

use App\Services\File\FileMaintenanceService;
use Illuminate\Support\Facades\Log;

class BackupService extends FileMaintenanceService
{
    /**
     * Check if the backup directory has enough free space to store a system
     * backup.
     */
    public function verifyBackupDiskSpace(): bool
    {
        $backupFreeSpace = disk_free_space(storage_path('/backups'));
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
