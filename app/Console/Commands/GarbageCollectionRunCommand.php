<?php

namespace App\Console\Commands;

use App\Jobs\GarbageCollectionJob;
use App\Models\CustomerEquipment;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        // new GarbageCollectionJob;

        GarbageCollectionJob::dispatch();

        return Command::SUCCESS;
    }


}
