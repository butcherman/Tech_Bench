<?php

namespace App\Console\Commands\Maint;

use App\Actions\Maintenance\RestoreBackup;
use App\Services\Maintenance\BackupRestoreService;
use App\Services\Maintenance\BackupService;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class BackupRestoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Tech Bench from a backup';

    /**
     * Constructor will inject the Restore Service Class
     */
    public function __construct(
        protected RestoreBackup $action,
        protected BackupRestoreService $svc,
        protected BackupService $backups
    ) {
        parent::__construct();
    }

    /**
     * Execute the command.
     */
    public function handle(): int
    {
        $this->components->alert('Database Restore');
        $this->components->alert(
            'WARNING: RESTORING DATABASE WILL OVERWRITE ALL EXISTING DATA'
        );
        $this->components->alert('PROCEED WITH CAUTION');

        // Select Backup file to Restore
        $backupChoice = select(
            label: 'Select Backup File to Restore',
            options: $this->backups->all()->pluck('backup_name'),
        );

        $this->components->alert('You are about to restore '.$backupChoice);

        $continue = confirm(
            label: 'Are you sure you want to continue?',
            default: false,
        );

        if (! $continue) {
            $this->info('Canceling');

            return 1;
        }

        $this->action->__invoke($backupChoice);

        return 0;
    }
}
