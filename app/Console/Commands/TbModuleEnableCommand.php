<?php

namespace App\Console\Commands;

use App\Traits\ModuleTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;

class TbModuleEnableCommand extends Command
{
    use ModuleTrait;

    protected $signature   = 'tb_module:enable {module}';
    protected $description = 'Enable a new or disabled Tech Bench Module';

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
        Log::notice('Running Command tb_module:enable for Module '.$this->argument('module'));

        $module = Module::find($this->argument('module'));

        //  Verify that the module files actually exist
        if(!$module)
        {
            $this->error('Unable to find module specified.');
            $this->error('Please make sure that the files are loaded and the name is spelled correctly');
            return 0;
        }

        //  Verify that the module is not already installed
        if($module && $module->isEnabled())
        {
            $this->error('The '.$this->argument('module').' Module is already installed and enabled');
            return 0;
        }

        $this->line('Found Module '.$this->argument('module'));
        $this->line('Checking Requirements');

        //  Verify the Tech Bench meets all module requirements
        if($failed = $this->checkRequirements($module))
        {
            $this->error('Prerequisite check failed!!');
            $this->error('Unable to continue until the following conditions are met');
            $this->newLine();

            if(isset($failed['version']))
            {
                $this->error('Tech Bench must be at Version '.$failed['version']['requires'].' or higher.');
                $this->error('Current Tech Bench Version - '.$failed['version']['tb_version']);
            }

            if(isset($failed['modules']))
            {
                foreach($failed['modules'] as $need)
                {
                    $this->error('The '.$need.' Module must be installed');
                }
            }

            return 0;
        }

        $this->info('Requirements passed');
        $this->line('Installing Module');
        $this->line('This may take some time.  Please wait...');
        $this->newLine();

        $module->enable();
        $this->runMigrations($module);
        $this->installDependencies($module);
        $this->updateCache();

        $this->newLine(2);
        $this->info('Module Installed');
        $this->info('Users may have to close and re-open browser before they can use the new module');

        return 0;
    }
}
