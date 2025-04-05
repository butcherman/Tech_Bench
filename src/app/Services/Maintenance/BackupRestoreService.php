<?php

namespace App\Services\Maintenance;

use App\Exceptions\Maintenance\BackupFailedException;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Exceptions\Maintenance\RestoreFailedException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;
use ZanySoft\Zip\Zip;

class BackupRestoreService extends BackupService
{
    /**
     * Zip Object with the backup archive.
     *
     * @var Zip
     */
    protected $archive;

    /**
     * Temp Directory for extracted backup files
     */
    protected $tmpArchive = 'restore-tmp/';

    /**
     * Delete the extracted temporary files.
     */
    public function deleteExtractedFiles(): void
    {
        $this->storage->deleteDirectory($this->tmpArchive);
    }

    /**
     * Extract the backup file to a temporary directory.
     */
    public function extractBackup(): void
    {
        $this->archive->extract($this->storage->path($this->tmpArchive));
    }

    /**
     * Mount/Open a zip archive so it can be examined.
     */
    public function openBackupArchive(string $backupFile): void
    {
        if (!$this->doesBackupExist($backupFile)) {
            throw new BackupFileMissingException;
        }

        $archivePath = $this->storage->path($this->backupBaseName . $backupFile);
        $this->archive = new Zip;

        if (!$this->archive->check($archivePath)) {
            throw new BackupFileInvalidException('File failed Archive Check');
        }

        $this->archive->open($archivePath);
    }

    /**
     * Wipe the existing database and restore the backed up database
     */
    public function restoreDatabase(): bool
    {
        try {
            // Drop all Database Tables
            DB::connection(DB::getDefaultConnection())
                ->getSchemaBuilder()
                ->dropAllTables();
            DB::reconnect();
        } catch (QueryException $e) {
            report($e);
            throw new RestoreFailedException('Unable to modify database');
        }

        // Get the DB File
        $dbPath = $this->storage
            ->path($this->tmpArchive . 'db-dumps/mysql-tech-bench.sql');
        $dbFile = file($dbPath);

        // Insert the Database file one section at a time.
        $currentLine = '';
        foreach ($dbFile as $line) {
            if ($line !== "\n") {
                $currentLine .= $line;
            } else {
                try {

                    DB::unprepared($currentLine);
                    $currentLine = '';
                } catch (QueryException $e) {
                    throw new BackupFailedException($e->getMessage());
                }
            }
        }

        // Run any migrations to get the DB up to date with the current version.
        Artisan::call('migrate --force');

        return true;
    }

    /**
     * Restore SSL Certificate
     */
    public function restoreCert(): void
    {
        $basePath = base_path('keystore');
        $backedUpPath = $this->storage->path($this->tmpArchive . 'app/keystore');

        // Wipe the current filesystem.
        $this->wipeDirectory($basePath);

        // Restore backed up files
        $this->restoreFiles($basePath, $backedUpPath);
    }

    /**
     * Restore the .env file
     */
    public function restoreEnvironmentFile(): void
    {
        $env = $this->storage->get($this->tmpArchive . 'app/.env');

        File::put(base_path() . DIRECTORY_SEPARATOR . '.env', $env);
    }

    /**
     * Restore the log files from the backup
     */
    public function restoreLogFiles(): void
    {
        $basePath = storage_path('logs');
        $backedUpPath = $this->storage
            ->path($this->tmpArchive . 'app/storage/logs');

        // Wipe the current filesystem.
        $this->wipeDirectory($basePath);

        // Restore backed up files
        $this->restoreFiles($basePath, $backedUpPath);
    }

    /**
     * Wipe existing file system and restore backed up files.
     */
    public function restoreFileSystem(): void
    {
        $basePath = storage_path('app');
        $backedUpPath = $this->storage
            ->path($this->tmpArchive . 'app/storage/app');

        // Wipe the current filesystem.
        $this->wipeDirectory($basePath);

        // Restore backed up files
        $this->restoreFiles($basePath, $backedUpPath);
    }

    /**
     * Restore a portion of the backed up files
     */
    protected function restoreFiles(string $restoreBase, string $backedUpPath): void
    {
        // Restore backed up files
        $filesToRestore = $this->getFileList($backedUpPath);

        foreach ($filesToRestore as $sourceFile) {
            $destination = $restoreBase . str_replace($backedUpPath, '', $sourceFile);

            // Make sure destination directory exists
            $pathInfo = pathinfo($destination);
            if (!File::isDirectory($pathInfo['dirname'])) {
                File::makeDirectory($pathInfo['dirname'], 0775, true);
            }

            File::move($sourceFile, $destination);
        }
    }

    /**
     * Validate that a backup file contains all files necessary to restore
     * Tech Bench database and file structure.
     */
    public function validateBackupFile(): bool
    {
        $structureFiles = [
            'app/.env',
            'app/keystore/version',
            'app/storage/app/.gitignore',
            'app/storage/logs/.gitignore',
            'db-dumps/mysql-tech-bench.sql',
        ];

        if (!$this->archive) {
            throw new BackupFileInvalidException('No Archive Selected');
        }

        foreach ($structureFiles as $file) {
            if (!$this->archive->has($file)) {
                throw new BackupFileInvalidException('Missing ' . $file);
            }
        }

        return true;
    }

    /**
     * Validate that a backup file contains a version file equal to or less
     * than the current application version.
     */
    public function validateBackupVersion(): bool
    {
        $this->archive->extract(
            $this->storage->path($this->tmpArchive),
            ['app/keystore/version']
        );

        $versionText = $this->storage->get('restore-tmp/app/keystore/version');
        $backupVersion = explode(' ', $versionText)[0];
        $appVersion = (new Version)->compact();

        $isValid = version_compare($appVersion, $backupVersion);

        if ($isValid !== 1) {
            throw new BackupFileInvalidException(
                'Backup Version is a Newer Version than the Tech Bench'
            );
        }

        return true;
    }

    /**
     * Verify that the directories to restore files are all writable
     */
    public function verifyRestoreFilesystemWritable(): bool
    {
        $pathList = config('backup.backup.source.files.include');

        // Make a file and directory in the base locations
        foreach ($pathList as $path) {
            if (File::isDirectory($path)) {
                try {
                    file_put_contents(
                        $path . DIRECTORY_SEPARATOR . 'test_file',
                        $path
                    );
                    File::makeDirectory(
                        $path . DIRECTORY_SEPARATOR . 'test_directory',
                        0775
                    );
                } catch (Exception $e) {
                    report($e);

                    return false;
                }
            }
        }

        return true;
    }
}
