<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

class ActivateModuleCommand extends Command
{
    protected $signature   = 'tb_module:activate {module}';
    protected $description = 'Activate a new Tech Bench Add On Module';

    protected $module;

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

        //  Verify module has config file
        $this->line('Activating Module '.$this->module);
        if(!$this->checkForConfig())
        {
            $this->error('Config file is missing');
            return 1;
        }

        //  Enable the module
        Artisan::call('module:enable '.$this->module->getStudlyName());

        //  Run any migrations
        $this->line('Setting up database');
        Artisan::call('migrate');

        //  Combine the new Javascript files
        $this->line('Creating Javascript files (this may take some time)');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.' && npm run production');

        $this->info('Module has been activated');

        return Command::SUCCESS;
    }

    protected function checkForConfig()
    {
        return file_exists($this->module->getPath().DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'config.php');
    }
}
