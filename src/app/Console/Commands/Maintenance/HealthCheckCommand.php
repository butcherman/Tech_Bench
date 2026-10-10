<?php

namespace App\Console\Commands\Maintenance;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('health:check')]
#[Description('Determines if the application containers are running in a healthy state')]
class HealthCheckCommand extends Command
{
    /**
     * Verify that the application containers are running.
     */
    public function handle()
    {
        DB::select('SELECT 1');

        return self::SUCCESS;
    }
}
