<?php

namespace App\Service\Maint;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;
use ZanySoft\Zip\Zip;

class RestoreService extends BackupService
{
    protected $archive;

    protected $restoreTmpPath;

    public function __construct()
    {
        parent::__construct();

        $this->restoreTmpPath = storage_path('backups'.DIRECTORY_SEPARATOR.'restore-tmp');
    }

    /**
     * Return the temporary restore path
     */
    public function getRestorePath()
    {
        return $this->restoreTmpPath;
    }

    /**
     * Validate that a backup file is in fact for the Tech Bench
     */
    public function validateBackupFile(string $fileName)
    {
        if (
            ! $this->doesBackupExist($fileName) ||
            ! $this->validateFileExtension($fileName)
        ) {
            return false;
        }

        $this->openBackupFile($fileName);

        return $this->validateFolderStructure($fileName);
    }

    /**
     * Open a backup archive
     */
    public function openBackupFile(string $fileName)
    {
        $path = Storage::disk('backups')->path($this->backupName.$fileName);
        $zip = new Zip;

        if ($zip->check($path)) {
            $this->archive = $zip->open($path);
        }
    }

    /**
     * Get the version of the backup
     */
    public function getBackupVersion()
    {
        $this->archive->extract(Storage::disk('backups')->path('restore-tmp/'), ['app/version.txt']);
        $verText = Storage::disk('backups')->get('restore-tmp/app/version.txt');

        return explode(' ', $verText)[1];
    }

    /**
     * Check the extension name of the selected file
     */
    protected function validateFileExtension(string $filename)
    {
        $fileParts = pathinfo($filename);
        if ($fileParts['extension'] !== 'zip') {
            return false;
        }

        return true;
    }

    /**
     * Extract the backup to a temporary directory
     */
    public function extractBackup()
    {
        $this->archive->extract(Storage::disk('backups')->path('restore-tmp/'));

        return true;
    }

    /**
     * Verify that the zip file has all of the necessary files
     */
    protected function validateFolderStructure(string $fileName)
    {
        if (! $this->archive) {
            return false;
        }

        if (
            ! $this->archive->has('app/.env') ||
            ! $this->archive->has('app/version.txt') ||
            ! $this->archive->has('app/storage/app/.gitignore') ||
            ! $this->archive->has('app/storage/logs/.gitignore') ||
            ! $this->archive->has('db-dumps/mysql-tech-bench.sql')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Restore database
     */
    public function restoreDatabase()
    {
        try {
            // Drop all Database Tables
            DB::connection(DB::getDefaultConnection())
                ->getSchemaBuilder()
                ->dropAllTables();
            DB::reconnect();
        } catch (QueryException $e) {
            return false;
        }

        // Get the DB File
        $dbPath = 'restore-tmp/db-dumps/mysql-tech-bench.sql';
        $dbFile = $this->storage->get($dbPath);

        // Restore the DB file
        DB::unprepared($dbFile);
        Artisan::call('migrate --force');

        return true;
    }

    /**
     * Restore files
     */
    public function restoreFiles(array $restorePaths)
    {
        foreach ($restorePaths as $path) {
            // We don't want to move the .env file just yet
            if (! preg_match('/.env/', $path)) {
                $this->wipeDirectory($path);

                $filesToMove = File::allFiles($this->restoreTmpPath.$path);
                foreach ($filesToMove as $moveMe) {
                    $destination = str_replace($this->restoreTmpPath, '', $moveMe);

                    $pathInfo = pathinfo($destination);
                    // Make sure the destination directory exists
                    if (! File::isDirectory($pathInfo['dirname'])) {
                        File::makeDirectory($pathInfo['dirname'], 0775, true);
                    }
                    // Move the file
                    File::move($moveMe, $destination);
                }

            }
        }

        return true;
    }

    /**
     * Remove all files within a selected disk except for original scaffolding files
     */
    protected function wipeDirectory($dirPath)
    {
        // Remove all files from the disk
        $fileList = File::allFiles($dirPath, true);

        foreach ($fileList as $file) {
            // If the folder has a .gitignore, it is part of the scaffolding and is kept
            if (! preg_match('/.gitignore/', $file)) {
                File::delete($file);
            }
        }

        // Remove all directories from the disk
        $dirList = File::directories($dirPath);
        foreach ($dirList as $dir) {
            $dirFiles = File::allFiles($dir, true);
            if (empty($dirFiles)) {
                File::deleteDirectory($dir);
            }
        }
    }

    /**
     * Move the .env file from backup to live system
     */
    public function restoreEnv()
    {
        $env = $this->storage->get('restore-tmp/app/.env');
        File::put(base_path().DIRECTORY_SEPARATOR.'.env', $env);

        return true;
    }
}
