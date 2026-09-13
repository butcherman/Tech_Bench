<?php

use App\Jobs\Maintenance\CheckAzureCertificateJob;
use App\Jobs\Maintenance\CheckSslCertificateJob;
use App\Jobs\Maintenance\CleanImageFoldersJob;
use App\Jobs\Maintenance\GarbageCollectionJob;
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

/*
|-------------------------------------------------------------------------------
| Daily Maintenance Jobs
|-------------------------------------------------------------------------------
*/
Schedule::job(new CheckSslCertificateJob)->daily();
Schedule::job(new CheckAzureCertificateJob)->daily();
Schedule::job(new GarbageCollectionJob)->daily();
Schedule::command('backup:run')
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
