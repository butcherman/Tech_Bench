<?php

namespace App\Console;

use App\Jobs\GarbageCollectionJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        // $schedule->job(new GarbageCollectionJob)->daily();
        $schedule->command('telescope:prune')->daily();

        //  Nightly backup will only run if the task is
        if (config('app.backups.enabled')) {
            $schedule->command('tb_maintenance:backup')->dailyAt('03:00');
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
