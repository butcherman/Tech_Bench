<?php

namespace App\Console\Commands\Maint;

use App\Actions\Maintenance\RunDatabaseBackup;
use Illuminate\Console\Command;

class AppBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manual Tech Bench Backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Running Database Backup');

        new RunDatabaseBackup();

        $this->info('Backup Complete');
    }
}
