<?php

namespace App\Console\Commands;

use App\Jobs\GarbageCollectionJob;
use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
class GarbageCollectionRunCommand extends Command
{
    protected $signature = 'garbagecollection:run';

    protected $description = 'Basic maintenance script to wipe temporary and deleted data';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        GarbageCollectionJob::dispatch();

        return Command::SUCCESS;
    }
}
