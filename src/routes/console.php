<?php

use App\Jobs\Admin\CheckCertificateJob;
use Illuminate\Support\Facades\Schedule;

/**
 * Re-occurring maintenance
 */
Schedule::command('auth:clear-resets')->everyFifteenMinutes();
Schedule::command('horizon:snapshot')->everyFifteenMinutes();
Schedule::command('model:prune')->daily();
Schedule::job(new CheckCertificateJob)->daily();
