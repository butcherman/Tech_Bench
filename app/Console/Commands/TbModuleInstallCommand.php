<?php

namespace App\Console\Commands;

use App\Traits\ModuleTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

class TbModuleInstallCommand extends Command
{
    use ModuleTrait;

    protected $signature   = 'tb_module:install';
    protected $description = 'Download an approved Module from the Tech Bench Modules Repository';

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
        Log::info('Running Command tb_module:install');

        $this->line('Checking for available Tech Bench Modules');

        $modList = $this->getAvailableModules();
        $modName = Arr::pluck($modList, 'module_name');

        //  Have user select which module to install
        $install = $this->choice('Select which Module to install', array_merge(['Exit'], $modName));
        if($install === 'Exit')
        {
            $this->line('Exiting');
            return 0;
        }

        $moduleData = $modList[Str::studly($install)];


        //  Check to see if the module is already installed
        $alreadyInstalled = Module::find($moduleData['alias']);
        if($alreadyInstalled)
        {
            $this->line('The '.$moduleData['module_name'].' is already installed');
            $this->line('If the module is currently disabled, run the "tb_module:enable '.$moduleData['alias'].'" command');
            $this->line('Exiting.');
            return 0;
        }

        //  Check to make sure that the Tech Bench can handle the Module
        if($failed = $this->checkJsonRequirements($moduleData))
        {
            $this->error('Prerequisite check failed!!');
            $this->error('Unable to continue until the following conditions are met');
            $this->newLine();

            if(isset($failed['max_version']))
            {
                $this->error('Tech Bench must be at Version '.$failed['max_version']['requires'].' or lower.');
                $this->error('Current Tech Bench Version - '.$failed['max_version']['tb_version']);
            }

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

        //  Install the module
        $this->line('Downloading '.$moduleData['module_name']);
        $package = $this->downloadModule($moduleData);
        $this->line('Opening package archive');
        $this->openModulePackage($package);
        $this->line('Installing Module');
        $this->line('This may take some time.  Please wait...');
        $this->installModule($moduleData);

        //  Once Module files are in place, we can use the Module Data to finish setting up the Module
        $module = Module::find($moduleData['alias']);
        $module->enable();
        $this->runMigrations($module);
        $this->installDependencies($module);

        //  Cleanup leftover files
        $this->cleanupPackage($package);

        $this->newLine(2);
        $this->info('Module Installed');
        $this->info('Users may have to close and re-open browser before they can use the new module');

        return 0;
    }
}
