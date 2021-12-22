<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\GarbageCollectionJob;

class GarbageCollectionRunCommand extends Command
{
    protected $signature   = 'garbagecollection:run';
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
