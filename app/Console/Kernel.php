<?php

namespace App\Console;

use App\Jobs\ApplicationBackupJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\GarbageCollectionJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();
        $schedule->job(new GarbageCollectionJob)->daily();

        //  Nightly backup will only run if the task is enabled
        if(config('app.backups.enabled'))
        {
            $schedule->job(new ApplicationBackupJob(true, true))->dailyAt('02:00');
        }
    }

    /**
     * Register the commands for the application
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
