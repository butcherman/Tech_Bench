<?php

namespace App\Console\Commands\Maint;

use App\Actions\Maintenance\RunDatabaseBackup;
use Illuminate\Console\Command;

class AppBackupCommand extends Command
{
    /**
     * The name and signature of the console command
     */
    protected $signature = 'app:backup';

    /**
     * The console command description
     */
    protected $description = 'Manual Tech Bench Backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        new RunDatabaseBackup();
    }
}
