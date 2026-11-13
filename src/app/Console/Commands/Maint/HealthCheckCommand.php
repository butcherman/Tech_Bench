<?php

namespace App\Console\Commands\Maint;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Throwable;

class HealthCheckCommand extends Command
{
    protected $signature = 'health:check';

    protected $description = 'Determines if the application is healthy';

    public function handle(): int
    {
        try {
            DB::select('SELECT 1');

            Redis::connection()->ping();

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Application healthcheck failed.');
            report($exception);

            return self::FAILURE;
        }
    }
}
