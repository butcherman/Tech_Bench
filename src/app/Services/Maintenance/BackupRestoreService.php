<?php

namespace App\Services\Maintenance;

use App\Exceptions\Maintenance\BackupFileInvalidException;
use Illuminate\Support\Facades\File;
use ZanySoft\Zip\Zip;

class BackupRestoreService
{
    // TODO - Refactor - Step 14, Step 15, Step 16

    public function __construct(protected BackupService $svc) {}

    /**
     * Create and open the Zip Archive with the backup file
     */
    public function mountArchive(string $backupName): Zip
    {
        $archive = new Zip;

        $this->svc->ensureExists($backupName);

        return $archive->open($this->svc->path($backupName));
    }

    /**
     * Validate that a backup file contains all files necessary to restore
     * Tech Bench database and file structure.
     */
    public function validateBackupStructure(Zip $archive): void
    {
        $structureFiles = [
            '.env',
            'keystore/version',
            'storage/app/.gitignore',
            'storage/logs/.gitignore',
        ];

        // Verify file structure exists
        foreach ($structureFiles as $file) {
            if (
                ! $archive->has('app/'.$file) &&
                ! $archive->has('var/www/html/'.$file)
            ) {
                $archive->close();

                throw new BackupFileInvalidException('Missing '.$file);
            }
        }

        // Verify DB backup exists
        if (! $archive->has('db-dumps/mysql-tech-bench.sql')) {
            throw new BackupFileInvalidException('Missing database dump');
        }
    }
}
