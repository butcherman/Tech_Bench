<?php

namespace App\Service\Maint;

use Illuminate\Support\Facades\Storage;

class BackupService
{
    protected $storage;

    protected $backupName;

    public function __construct()
    {
        $this->storage = Storage::disk('backups');
        $this->backupName = config('backup.backup.name').DIRECTORY_SEPARATOR;
    }

    /**
     * Determine if there is currently a backup job running
     */
    public function isBackupRunning()
    {
        return count($this->storage->directories('backup-temp')) > 0 ?
            true : false;
    }

    /**
     * Verify that a backup file exists based on the filename
     */
    public function doesBackupExist(string $fileName)
    {
        return $this->storage->exists($this->backupName.$fileName);
    }

    /**
     * Delete an existing backup file
     */
    public function deleteBackupFile(string $fileName)
    {
        $this->storage->delete($this->backupName.$fileName);
    }

    /**
     * Get a list of all backup files along with metadata
     */
    public function getBackupFiles()
    {
        $fileList = array_reverse($this->getFileList());

        return $this->buildMetaData($fileList);
    }

    /**
     * Get a list of just the backup files
     */
    protected function getFileList()
    {
        return $this->storage->files('tech-bench');
    }

    /**
     * Get the size and modified date of the backup files
     */
    protected function buildMetaData($fileList)
    {
        $newList = [];
        foreach ($fileList as $fileName) {
            $newList[] = [
                'name' => str_replace($this->backupName, '', $fileName),
                'size' => $this->storage->size($fileName),
                'modified' => $this->storage->lastModified($fileName),
            ];
        }

        return $newList;
    }
}
