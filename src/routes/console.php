<?php

use App\Jobs\Admin\CheckAzureCertificateJob;
use App\Jobs\Admin\CheckSslCertificateJob;
use Illuminate\Support\Facades\Schedule;

/*
|-------------------------------------------------------------------------------
| Maintenance commands run throughout the day
|-------------------------------------------------------------------------------
*/

Schedule::command('app:collect-garbage')->daily();
Schedule::command('telescope:prune')->daily();
Schedule::command('horizon:snapshot')->everyFifteenMinutes();
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('auth:clear-validation-codes')->everyFifteenMinutes();

// TODO - Create Jobs
Schedule::job(new CheckSslCertificateJob)->daily();
Schedule::job(new CheckAzureCertificateJob)->daily();
// Schedule::job(new NightlyBackupJob)->dailyAt('03:00');
// Schedule::job(new DailyCleanupJob)->dailyAt('06:00');
// Schedule::job(new ImageFileCleanupJob)->monthly();
