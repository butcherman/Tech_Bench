<?php

use App\Jobs\Admin\CheckCertificateJob;
use App\Jobs\Maintenance\DailyCleanupJob;
use App\Jobs\Maintenance\NightlyBackupJob;
use Illuminate\Support\Facades\Schedule;

/**
 * Re-occurring maintenance
 */
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('horizon:snapshot')->everyFifteenMinutes();
Schedule::command('model:prune')->daily();
Schedule::job(new CheckCertificateJob)->daily();
Schedule::job(new NightlyBackupJob())->dailyAt('03:00');
Schedule::job(new DailyCleanupJob())->dailyAt('06:00');
