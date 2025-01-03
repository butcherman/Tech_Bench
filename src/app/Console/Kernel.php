<?php

// TODO - Refactor

namespace App\Console;

use App\Jobs\Admin\CheckCertificateJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Re-occurring maintenance
        // $schedule->command('auth:clear-resets')->everyFifteenMinutes();
        // $schedule->command('horizon:snapshot')->everyFiveMinutes();
        // $schedule->command('model:prune')->daily();
        // $schedule->job(new CheckCertificateJob)->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
