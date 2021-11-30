<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

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

        //  Verify Module exists
        if(!$this->module)
        {
            $this->error('Unable to find module');
            return 1;
        }

        Artisan::call('module:disable '.$this->module->getStudlyName());

        $this->info('Module has been disabled');

        return Command::SUCCESS;
    }
}
