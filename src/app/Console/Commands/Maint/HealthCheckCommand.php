<?php

namespace App\Console\Commands\Maint;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'health:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Determines if the application is running';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::select('SELECT 1');

        return self::SUCCESS;
    }
}
