<?php

namespace App\Actions\Maintenance;

use App\Services\Maintenance\BackupRestoreService;
use App\Services\Misc\ConsoleOutputService;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class RestoreBackup
{
    public function __construct(
        protected BackupRestoreService $svc,
        protected ConsoleOutputService $output
    ) {}

    /**
     * Restore a selected backup and replace all existing data
     */
    public function __invoke(string $backupName)
    {
        $this->output->writeln('Restoring backup file '.$backupName);

        $transaction = $this->svc->prepareRestore($backupName);

        try {
            $this->output->writeLn('Putting application in Maintenance Mode');
            Artisan::call('down');

            $this->output->writeln('Creating Restore point');
            $this->svc->createRollbackSnapshot($transaction);

            $dbPath = $transaction->extractedPath().'/db-dumps/mysql-tech-bench.sql';
            $this->output->writeLn('Restoring Database');
            $this->svc->restoreDatabase($dbPath);

            $this->output->writeLn('Restoring files');
            $this->svc->restoreFileSystem($transaction);

            $this->output->writeLn('Validating restore process');
            $this->svc->verifyRestore();

            Artisan::call('migrate --force');

            $this->output->writeLn('Bringing application back online');
            Artisan::call('up');

            $this->output->writeLn('Cleaning up');
            $this->svc->cleanupTransaction($transaction);

            $this->output->writeLn('Backup Restored Successfully');
        } catch (Throwable $e) {
            $this->output->writeLn('Restore Failed.  Attempting Rollback...');

            try {
                $this->svc->rollback($transaction);

                Artisan::call('up');

                $this->output->writeLn(
                    'System Rolled Back to previous version.  Please check logs for additional information'
                );
            } catch (Throwable $newE) {
                $this->output->writeLn('Rollback Failed');
                throw $newE;
            }

            throw $e;
        }
    }
}
