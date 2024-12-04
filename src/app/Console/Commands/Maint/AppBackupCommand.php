<?php

// TODO - Refactor

namespace App\Console\Commands\Maint;

use App\Jobs\Maintenance\RunBackupJob;
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
        // $this->line('Running System Backup');

        // RunBackupJob::dispatchSync();

        $this->error('Oops, looks like I have not made it this far yet...');

        return 1;
    }
}
