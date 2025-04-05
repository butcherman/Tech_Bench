<?php

namespace App\Console\Commands\Maint;

use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Services\Maintenance\BackupRestoreService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class BackupRestoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Tech Bench from a backup';

    /**
     * Constructor will inject the Restore Service Class
     */
    public function __construct(protected BackupRestoreService $svc)
    {
        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->components->alert('Database Restore');
        $this->components->alert(
            'WARNING: RESTORING DATABASE WILL OVERWRITE ALL EXISTING DATA'
        );
        $this->components->alert('PROCEED WITH CAUTION');

        // Select Backup file to Restore
        $backupChoice = select(
            label: 'Select Backup File to Restore',
            options: collect($this->svc->getBackupListWithMetaData())->pluck('name'),
        );

        /*
        |-----------------------------------------------------------------------
        | Mount and Validate the backup file
        |-----------------------------------------------------------------------
        */
        spin(
            message: 'Validating Backup File',
            callback: fn() => $this->validateSelectedBackup($backupChoice),
        );

        $this->info('Backup File is Valid');

        /*
        |-----------------------------------------------------------------------
        | Should Logs and SSL Cert be restored?
        |-----------------------------------------------------------------------
        */
        $restoreEnv = confirm(
            label: 'Restore Environment File?',
            default: true,
        );

        $restoreLogs = confirm(
            label: 'Restore Log Files?',
            default: true,
        );

        $restoreCert = confirm(
            label: 'Restore SSL Certificate?',
            default: true,
        );

        $this->components->alert('WARNING:  All Existing Data Will Be Erased');
        warning('Selected Backup File - ' . $backupChoice);

        $continue = confirm(
            label: 'Continue?',
            default: false,
        );

        if (!$continue) {
            $this->abortRestore('Recovery Confirmation Aborted');
        }

        /*
        |-----------------------------------------------------------------------
        | Start the Restore Process - Restore Database
        |-----------------------------------------------------------------------
        */

        $this->components->info(
            'Starting Database Restore Process, this may take some time.'
        );
        $this->components->info('Taking Tech Bench Offline');

        $this->call('down');

        spin(
            message: 'Extracting Backup',
            callback: fn() => $this->svc->extractBackup(),
        );

        $this->components->info('Backup Extracted');

        spin(
            message: 'Restoring Database',
            callback: fn() => $this->svc->restoreDatabase()
        );

        $this->components->info('Database Restored');

        /*
        |-----------------------------------------------------------------------
        | Restore filesystem.
        |-----------------------------------------------------------------------
        */

        spin(
            message: 'Restoring Filesystem',
            callback: fn() => $this->svc->restoreFileSystem()
        );

        $this->components->info('Filesystem Restored');

        if ($restoreEnv) {
            spin(
                message: 'Restoring Environment File',
                callback: fn() => $this->svc->restoreEnvironmentFile()
            );

            $this->components->info('Environment File Restored');
        }

        if ($restoreLogs) {
            spin(
                message: 'Restoring Log Files',
                callback: fn() => $this->svc->restoreLogFiles()
            );

            $this->components->info('Log Files Restored');
        }

        if ($restoreCert) {
            spin(
                message: 'Restoring SSL Certificate',
                callback: fn() => $this->svc->restoreCert()
            );

            $this->components->info('SSL Certificate Restored');
        }

        $this->components->info('Restore Process Complete');
        $this->components->info('Performing Cleanup Tasks');

        $this->cleanup(true);
    }

    /**
     * Abort the Restore Process with error.
     */
    private function abortRestore(string $reason): void
    {
        Log::error('Aborting Restore Process.  Reason - ' . $reason);

        $this->components->error('Backup Recovery Failed.  Aborting.');

        $this->cleanup();

        $this->fail($reason);
    }

    /**
     * Cleanup all temporary files and re-enable application
     */
    protected function cleanup(bool $reboot = false): void
    {
        $this->svc->deleteExtractedFiles();

        // // Rebuild assets and cache new config
        if (App::environment('production')) {
            // Clear and re-cache all config data
            $this->call('optimize:clear');
            $this->call('breadcrumbs:cache');
            $this->call('optimize');

            // Rebuild all JS application files
            Process::run('npm run build');
        }

        if (App::isDownForMaintenance()) {
            $this->call('up');
        }

        // TODO - Reboot Tech Bench
    }

    /**
     * Open and validate the Tech Bench backup file.
     */
    private function validateSelectedBackup(string $selectedBackup): bool
    {
        try {
            $this->svc->openBackupArchive($selectedBackup);
            $this->svc->validateBackupFile();
            $this->svc->validateBackupVersion();
            $this->svc->verifyRestoreFilesystemWritable();
        } catch (BackupFileInvalidException $e) {
            $e->report();

            $this->abortRestore(
                'Backup File is invalid.  Check logs for additional information'
            );
        }

        return true;
    }
}
