<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\ApplicationBackupJob;

class TbBackupCommand extends Command
{
    protected $signature   = 'tb_backup:run
                                {--databaseOnly : Only backup the configuration database}
                                {--filesOnly    : Only backup the file system}';
    protected $description = 'Creates a backup of the Tech Bench application';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Manually run a system backup
     */
    public function handle()
    {
        $this->line('Starting application backup');
        $this->line('Please wait...');

        $files    = $this->option('databaseOnly') ? false : true;
        $database = $this->option('filesOnly') ? false : true;

        ApplicationBackupJob::dispatch($database, $files)->onConnection('sync');

        $this->line('Backup Completed');
        return 0;
    }
}
