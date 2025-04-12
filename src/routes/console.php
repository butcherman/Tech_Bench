<?php

use App\Jobs\Maintenance\CheckAzureCertificateJob;
use App\Jobs\Maintenance\CheckSslCertificateJob;
use App\Jobs\Maintenance\CleanImageFoldersJob;
use App\Jobs\Maintenance\GarbageCollectionJob;
use App\Jobs\Maintenance\NightlyBackupJob;
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
Schedule::job(new NightlyBackupJob)->dailyAt('03:00');
Schedule::job(new GarbageCollectionJob)->daily();

/*
|-------------------------------------------------------------------------------
| Weekly or Monthly Maintenance Jobs
|-------------------------------------------------------------------------------
*/
Schedule::job(new CleanImageFoldersJob)->monthly();
