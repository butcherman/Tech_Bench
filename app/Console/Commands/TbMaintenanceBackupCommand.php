<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TbMaintenanceBackupCommand extends Command
{
    protected $signature = 'tb_maintenance:backup
                                        {--databaseOnly : Only backup the configuration database}
                                        {--filesOnly    : Only backup the file system}';
    protected $description = 'Generate a backup of the Tech Bench application';

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->line('Starting Application Backup');
        $this->line('Please wait...');

        Log::info('Starting Application Backup');

        //  Verify that no more than 70% of the HDD storage space has been used
        $freeSpace  = disk_free_space('/app');
        $totalSpace = disk_total_space('/app');
        $usedSpace  = $totalSpace - $freeSpace;
        $percentage = round(($usedSpace / $totalSpace * 100), 2);

        if($percentage > 70)
        {
            Log::critical('Unable to backup file system, more than 70% of the available storage space in use');
            $this->call('backup:run', ['--only-db' => true]);
            return 0;
        }

        if($this->option('databaseOnly'))
        {
            $this->call('backup:run', ['--only-db' => true]);
        }
        elseif($this->option('filesOnly'))
        {
            $this->call('backup:run', ['--only-files' => true]);
        }
        else
        {
            $this->call('backup:run');
        }

        Log::info('Application backup completed');
        $this->info('Backup successful');

        return 0;
    }
}
