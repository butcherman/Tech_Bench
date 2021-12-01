<?php

namespace App\Console\Commands;

use Nwidart\Modules\Facades\Module;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DisableModuleCommand extends Command
{
    protected $signature  = 'tb_module:disable {module}';
    protected $description = 'Disable, but do not remove a Tech Bench Add On Module';

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
        $this->module = Module::find($this->argument('module'));
        Log::info('Disable Module Command running for '.$this->argument('module'));

        //  Verify Module exists
        if(!$this->module)
        {
            $this->error('Unable to find module');
            Log::critical('Unable to find module '.$this->argument('module'));
            return 1;
        }

        $this->call('module:disable '.$this->module->getStudlyName());

        $this->info('Module has been disabled');
        Log::notice('Module '.$this->argument('module').' has been disabled');

        return Command::SUCCESS;
    }
}
