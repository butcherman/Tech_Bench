<?php

namespace App\Console\Commands\Maint;

use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Laravel\Prompts\Output\ConsoleOutput;

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
        $this->line('Running System Backup');

        // Artisan::call('backup:run', [], new ConsoleOutput);
        RunBackupJob::dispatchSync();
    }
}
