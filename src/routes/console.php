<?php

use App\Jobs\Admin\CheckAzureCertificateJob;
use App\Jobs\Admin\CheckCertificateJob;
use App\Jobs\Maintenance\DailyCleanupJob;
use App\Jobs\Maintenance\ImageFileCleanupJob;
use App\Jobs\Maintenance\NightlyBackupJob;
use Illuminate\Support\Facades\Schedule;

/**
 * Re-occurring maintenance
 */
Schedule::command('auth:clear-validation-codes')->everyFifteenMinutes();
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('horizon:snapshot')->everyFifteenMinutes();
Schedule::command('model:prune')->daily();

Schedule::job(new CheckCertificateJob)->daily();
Schedule::job(new CheckAzureCertificateJob)->daily();
Schedule::job(new NightlyBackupJob)->dailyAt('03:00');
Schedule::job(new DailyCleanupJob)->dailyAt('06:00');
Schedule::job(new ImageFileCleanupJob)->monthly();
