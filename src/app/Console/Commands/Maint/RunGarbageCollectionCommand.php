<?php

namespace App\Console\Commands\Maint;

use App\Service\Maint\GarbageCollectionService;
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Running Garbage Collection');

        new GarbageCollectionService();

        $this->info('Garbage Collection Completed');
    }
}
