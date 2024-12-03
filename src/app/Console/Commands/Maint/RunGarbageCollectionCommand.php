<?php

namespace App\Console\Commands\Maint;

use App\Actions\Maintenance\GarbageCollection;
use Illuminate\Console\Command;

class RunGarbageCollectionCommand extends Command
{
    /**
     * The name and signature of the console command
     */
    protected $signature = 'app:collect-garbage';

    /**
     * The console command description
     */
    protected $description = 'Nightly maintenance to clear failed jobs and dangling files';

    /**
     * Execute the console command.
     */
    public function handle(GarbageCollection $svc): void
    {
        $this->line('Running Garbage Collection');

        $svc();

        $this->info('Garbage Collection Completed');
    }
}
