<?php

namespace App\Services\Maintenance;

use App\DTO\Maintenance\RestoreTransaction;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Exceptions\Maintenance\RestoreFailedException;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use PragmaRX\Version\Package\Version;
use Throwable;
use ZanySoft\Zip\Zip;

class BackupRestoreService
{
    protected string $tmpPath = '/tmp/restore/';

    protected ?Zip $archive;

    public function __construct(
        protected BackupService $svc,
        protected ConsoleOutputService $output
    ) {}

    public function prepareRestore(string $backupName): RestoreTransaction
    {
        $this->svc->ensureExists($backupName);

        $restoreId = now()->format('Ymd-His').'-'.Str::random(5);
        $restorePath = $this->tmpPath.$restoreId;

        File::ensureDirectoryExists($restorePath);

        $transaction = new RestoreTransaction(
            id: $restoreId,
            backupName: $backupName,
            path: $restorePath,
        );

        File::ensureDirectoryExists($transaction->extractedPath());
        File::ensureDirectoryExists($transaction->rollbackPath());
        File::ensureDirectoryExists($transaction->rollbackStoragePath());

        // Prep the restore process
        try {
            $this->output->writeLn('Mounting backup');
            $this->mountArchive($backupName);

            $this->output->writeLn('Validating backup');
            $this->validateBackupStructure();
            $this->validateBackupVersion($transaction->extractedPath());

            $this->output->writeLn('Extracting backup');
            $this->archive->extract($transaction->extractedPath());
            $this->validateExtractedBackup($transaction);

            $this->archive->close();
            $this->archive = null;

            return $transaction;
        } catch (Throwable $e) {
            $this->cleanupTransaction($transaction);

            throw $e;
        }
    }

    /**
     * Create and open the Zip Archive with the backup file
     */
    public function mountArchive(string $backupName): Zip
    {
        $this->archive = new Zip;

        $this->svc->ensureExists($backupName);

        $this->archive->open($this->svc->path($backupName));

        return $this->archive;
    }

    /**
     * Restore the database
     */
    public function restoreDatabase(string $dbBackup): void
    {
        $config = config('database.connections.mysql');

        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['database']),
        );

        $process = proc_open(
            $command,
            [
                0 => ['file', $dbBackup, 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
        );

        if (! is_resource($process)) {
            throw new RestoreFailedException(
                'Unable to start MySQL restore process.'
            );
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new RestoreFailedException(
                'MySQL restore failed: '.$stderr
            );
        }
    }

    /**
     * Restore the file system
     */
    public function restoreFileSystem(RestoreTransaction $transaction): void
    {
        $path = $this->findExtractedBasePath($transaction->extractedPath());
        $storagePath = storage_path('app');

        $envPath = App::environmentFilePath();

        File::moveDirectory($path.'/storage', $storagePath);
        File::move($path.'/.env', $envPath);
    }

    /*
    |---------------------------------------------------------------------------
    | Validation
    |---------------------------------------------------------------------------
    */

    /**
     * Validate that a backup file contains all files necessary to restore
     * Tech Bench database and file structure.
     */
    public function validateBackupStructure(): void
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
                ! $this->archive->has('app/'.$file) &&
                ! $this->archive->has('var/www/html/'.$file)
            ) {
                $this->archive->close();

                throw new BackupFileInvalidException('Missing '.$file);
            }
        }

        // Verify DB backup exists
        if (! $this->archive->has('db-dumps/mysql-tech-bench.sql')) {
            throw new BackupFileInvalidException('Missing database dump');
        }
    }

    /**
     * Verify that the full backup zip was extracted properly
     */
    protected function validateExtractedBackup(RestoreTransaction $transaction): void
    {
        $path = $transaction->extractedPath();

        $database = $path.'/db-dumps/mysql-tech-bench.sql';

        if (! File::exists($database)) {
            throw new BackupFileInvalidException(
                'Database dump was not extracted.'
            );
        }

        $basePath = $this->findExtractedBasePath($path);

        if (! File::isDirectory($basePath.'/storage/app')) {
            throw new BackupFileInvalidException(
                'Storage directory was not extracted.'
            );
        }

        if (! File::exists($basePath.'/.env')) {
            throw new BackupFileInvalidException(
                'Env file was not extracted',
            );
        }
    }

    /**
     * Validate that a backup file contains a version file equal to or less
     * than the current application version.
     */
    private function validateBackupVersion(string $extractionPath): void
    {
        if ($this->archive->has('app/keystore/version')) {
            $verPath = 'app/keystore/version';
        } else {
            $verPath = 'var/www/html/keystore/version';
        }

        $this->archive->extract(
            $extractionPath,
            [$verPath]
        );

        // $versionText = $this->storage->get('restore-tmp/app/keystore/version');
        $versionText = File::get($extractionPath.DIRECTORY_SEPARATOR.$verPath);
        $backupVersion = explode(' ', $versionText)[0];
        $appVersion = (new Version)->compact();

        $isValid = version_compare($backupVersion, $appVersion);

        if ($isValid === 1) {
            throw new BackupFileInvalidException(
                'Backup Version is a Newer Version than the Installed Tech Bench Version'.
                ' App Version: '.$appVersion.
                ' Backup Version: '.$backupVersion
            );
        }
    }

    /**
     * Verify DB tables and filesystem exists
     */
    public function verifyRestore(): void
    {
        $storagePath = storage_path('app');

        $baseDirectories = [
            '/private',
            '/public',
        ];

        $baseTables = [
            'backup_runs',
            'app_settings',
            'customers',
            'users',
        ];

        foreach ($baseDirectories as $dir) {
            if (! File::isDirectory($storagePath.$dir)) {
                throw new RestoreFailedException(
                    'Base file directory missing from restore process - '.$dir,
                );
            }
        }

        foreach ($baseTables as $table) {
            if (! DB::table($table)->exists()) {
                throw new RestoreFailedException(
                    'Base database table missing from restore process - '.$table
                );
            }
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Rollback
    |---------------------------------------------------------------------------
    */

    /**
     * Create a restore point in case of failure
     */
    public function createRollbackSnapshot(RestoreTransaction $transaction)
    {
        $this->createDatabaseSnapshot($transaction->rollbackDatabasePath());
        $this->createStorageRollback($transaction->rollbackStoragePath());

        $envPath = App::environmentFilePath();

        File::copy($envPath, $transaction->rollbackPath().'/.env');
    }

    /**
     * Create a database snapshot
     */
    private function createDatabaseSnapshot(string $path): void
    {
        $result = Process::run([
            'mysqldump',
            '--host='.config('database.connections.mysql.host'),
            '--port='.config('database.connections.mysql.port'),
            '--user='.config('database.connections.mysql.username'),
            '--password='.config('database.connections.mysql.password'),
            '--single-transaction',
            '--routines',
            '--triggers',
            config('database.connections.mysql.database'),
        ]);

        if ($result->failed()) {
            throw new RestoreFailedException(
                'Unable to create database rollback: '.
                $result->errorOutput()
            );
        }

        File::put($path, $result->output());
    }

    /**
     * Move the existing storage system into the rollback folder
     */
    private function createStorageRollback(string $path): void
    {
        $current = storage_path('app');
        File::moveDirectory($current, $path);
    }

    /**
     * Restore the Rollback Snapshot
     */
    public function rollback(RestoreTransaction $transaction): void
    {
        $storageRollback = $transaction->rollbackStoragePath();
        $current = storage_path('app');

        File::moveDirectory($storageRollback, $current);

        $dbFile = $transaction->rollbackDatabasePath();
        $this->restoreDatabase($dbFile);
    }

    /*
    |---------------------------------------------------------------------------
    | Cleanup
    |---------------------------------------------------------------------------
    */

    /**
     * Cleanup the temporary files created by the transaction
     */
    public function cleanupTransaction(RestoreTransaction $transaction): void
    {
        File::deleteDirectories($transaction->path);

        unset($transaction);
    }

    /*
    |---------------------------------------------------------------------------
    | Helpers
    |---------------------------------------------------------------------------
    */

    /**
     * Starting with V9, app files are stored in a different location.  Determine
     * proper path to look for files.
     */
    protected function findExtractedBasePath(string $path): string
    {
        $possiblePaths = [
            $path.'/app',
            $path.'/var/www/html',
        ];

        foreach ($possiblePaths as $filePath) {
            if (File::isDirectory($filePath)) {
                return $filePath;
            }
        }

        throw new BackupFileInvalidException(
            'Missing storage directory.'
        );
    }
}
