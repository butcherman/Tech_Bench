<?php

use App\Enums\BackupType;
use App\Jobs\Maintenance\CheckAzureCertificateJob;
use App\Jobs\Maintenance\CheckSslCertificateJob;
use App\Jobs\Maintenance\CleanImageFoldersJob;
use App\Jobs\Maintenance\GarbageCollectionJob;
use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Support\Facades\Schedule;

/*
|-------------------------------------------------------------------------------
| Maintenance commands run throughout the day
|-------------------------------------------------------------------------------
*/

Schedule::command('telescope:prune')->daily();
Schedule::command('horizon:snapshot')->everyFifteenMinutes();
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('auth:clear-validation-codes')->everyFifteenMinutes();
Schedule::command('tus:prune')->hourly();

/*
|-------------------------------------------------------------------------------
| Daily Maintenance Jobs
|-------------------------------------------------------------------------------
*/
Schedule::job(new CheckSslCertificateJob)->daily();
Schedule::job(new CheckAzureCertificateJob)->daily();
Schedule::job(new GarbageCollectionJob)->daily();
Schedule::job(new RunBackupJob(BackupType::Scheduled))
    ->dailyAt('03:00')
    ->when(
        fn () => (bool) config('backup.nightly_backup')
    )
    ->onOneServer();
Schedule::command('backup:clean')
    ->dailyAt('03:30')
    ->when(
        fn () => (bool) config('backup.nightly_cleanup')
    )
    ->onOneServer();

/*
|-------------------------------------------------------------------------------
| Weekly or Monthly Maintenance Jobs
|-------------------------------------------------------------------------------
*/
Schedule::job(new CleanImageFoldersJob)->monthly();
