<?php

namespace App\Console\Commands\Maint;

use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AppBackupCommand extends Command
{
    /**
     * The name and signature of the console command
     *
     * @var string
     */
    protected $signature = 'app:backup';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Manual Tech Bench Backup';

    /*
    |---------------------------------------------------------------------------
    | Manually trigger a backup from the command prompt
    |---------------------------------------------------------------------------
    */
    public function handle()
    {
        $this->line('Running System Backup');

        Log::info('Manual Backup called from Command Line');

        RunBackupJob::dispatchSync();
    }
}
