<?php

// TODO - Refactor

namespace App\Console\Commands\Maint;

use App\Service\Maint\RestoreService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use PragmaRX\Version\Package\Version;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class BackupRestoreCommand extends Command
{
    /**
     * The name and signature of the console command
     */
    protected $signature = 'app:backup-restore';

    /**
     * The console command description
     */
    protected $description = 'Restore a System Backup';

    protected $backupObj;

    protected $hasErrors = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->error('Oops, looks like I have not made it this far yet...');

        return 1;
        // $this->backupObj = new RestoreService;
        // $this->components->alert('Database Restore');

        // // Notify this is a major risk
        // $this->components->alert(
        //     'WARNING: RESTORING DATABASE WILL OVERWRITE ALL EXISTING DATA'
        // );
        // $this->components->alert('PROCEED WITH CAUTION');

        // // Have use select which backup file to use
        // $backupChoice = select(
        //     label: 'Select the upload to restore',
        //     options: $this->getBackupList()
        // );

        // // Confirm that this is the correct file
        // warning('You have selected '.$backupChoice);
        // $confirm = confirm(
        //     label: 'Are you sure you want to restore this backup?',
        //     default: false,
        // );

        // // If user selected no, exit script
        // if (! $confirm) {
        //     warning('Exiting');

        //     return;
        // }

        // /***********************************************************************
        //  * Validate the Backup File
        //  ***********************************************************************/
        // if (! spin(
        //     message: 'Verifying File is Valid Tech Bench Backup',
        //     callback: fn () => $this->backupObj->validateBackupFile($backupChoice)
        // )) {
        //     $this->components->error('Invalid Backup File');
        //     $this->components->error('Exiting');

        //     return;
        // } else {
        //     $this->components->success('Backup File is Valid');
        // }

        // /***********************************************************************
        //  * Start the Restore Process
        //  ***********************************************************************/
        // $this->components->info('Putting Application in Maintenance Mode');
        // $this->call('down');

        // if (! $this->validateBackupVersion()) {
        //     warning('Exiting');
        //     $this->cleanup();

        //     return;
        // }

        // /**
        //  * Extract the backup to temporary folder
        //  */
        // if (! spin(
        //     message: 'Extracting Backup Files',
        //     callback: fn () => $this->backupObj->extractBackup()
        // )) {
        //     $this->components->error('Unable to extract Backup File');
        //     $this->cleanup();

        //     return;
        // } else {
        //     $this->components->success('Backup File Extracted');
        // }

        // /**
        //  * Backup the .env file just in case
        //  */
        // File::copy(
        //     base_path().DIRECTORY_SEPARATOR.'.env',
        //     base_path().DIRECTORY_SEPARATOR.'.env.old',
        // );

        // /***********************************************************************
        //  * Restore the database
        //  ***********************************************************************/
        // if (! spin(
        //     message: 'Restoring Database',
        //     callback: fn () => $this->backupObj->restoreDatabase()
        // )) {
        //     $this->components->error('Restore Failed');
        //     $this->components->error('Unable to Restore Database');
        //     $this->cleanup();

        //     return;
        // } else {
        //     $this->components->success('Database Restored');
        // }

        // /***********************************************************************
        //  * Restore Disk Files
        //  ***********************************************************************/
        // $restorePaths = config('backup.backup.source.files.include');
        // if (! spin(
        //     message: 'Restoring Files',
        //     callback: fn () => $this->backupObj->restoreFiles($restorePaths)
        // )) {
        //     $this->components->error('Unable to restore files');
        //     $this->hasErrors = true;

        // } else {
        //     $this->components->success('Files Restored');
        // }

        // /**
        //  * Move the .env file
        //  */
        // if (! spin(
        //     message: 'Building Application Environment',
        //     callback: fn () => $this->backupObj->restoreEnv()
        // )) {
        //     $this->components->error('Unable to restore Environment File');
        //     $this->hasErrors = true;
        // } else {
        //     $this->call('app:validate-env', ['--force' => true]);
        //     $this->components->success('Environment Restored');
        // }

        // /**
        //  * Wrap up
        //  */
        // $this->cleanup();

        // /**
        //  * Display final message
        //  */
        // if ($this->hasErrors) {
        //     $this->components->alert('Backup completed, but some files were not properly moved');
        // } else {
        //     $this->components->success('Backup Completed Successfully');
        // }

        // $this->components->info('Please Reboot Tech Bench to Complete Restore Process');
    }

    /**
     * Get a list of Backup Files
     */
    protected function getBackupList()
    {
        $backupList = collect($this->backupObj->getBackupFiles());

        return $backupList->pluck('name');
    }

    /**
     * Verify that the backup version is the same or older than the app version
     */
    protected function validateBackupVersion()
    {
        $backupVersion = $this->backupObj->getBackupVersion();
        $appVersion = (new Version)->compact();

        $valid = version_compare($appVersion, $backupVersion);

        if ($valid == -1) {
            $this->components->error('Invalid Backup Version');
            $this->line('The Backup File is newer than the current Tech Bench Version');
            $this->line('Please update Tech Bench to '.$backupVersion.' or higher');

            return false;
        }

        return true;
    }

    /**
     * Cleanup all temporary files and re-enable application
     */
    protected function cleanup()
    {
        Storage::disk('backups')->deleteDirectory('restore-tmp');

        // Rebuild assets and cache new config
        if (App::environment('production')) {
            // Clear and re-cache all config data
            $this->call('optimize:clear');
            $this->call('breadcrumbs:cache');
            $this->call('optimize');

            // Rebuild all JS application files
            Process::run('npm run build');
        }

        $this->call('up');
    }
}
