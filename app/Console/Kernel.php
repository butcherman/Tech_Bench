<?php

namespace App\Console;

use App\Jobs\Maintenance\DailyBackupJob;
use App\Jobs\Maintenance\DailyCleanupJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();\
        $schedule->command('auth:clear-resets')->everyFifteenMinutes();
        $schedule->command('horizon:snapshot')->everyFiveMinutes();

        /**
         * Maintenance Jobs
         */
        $schedule->job(new DailyBackupJob)->dailyAt('03:00');
        $schedule->job(new DailyCleanupJob)->dailyAt('06:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // require base_path('routes/console.php');
    }
}
