<?php

namespace App\Console\Commands;

use App\Traits\ModuleTrait;
use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;

/**
 * @codeCoverageIgnore
 */
class TbModuleUpdateCommand extends Command
{
    use ModuleTrait;

    protected $signature   = 'tb_module:update';
    protected $description = 'Check for Module Updates';

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
        //  If there are no active modules, there is no need to run this command
        $activeModules = Module::allEnabled();
        if($activeModules == [])
        {
            $this->error('You have not loaded any modules.');
            $this->error('Exiting');

            return 0;
        }

        $this->line('Checking for Module Updates');
        $this->line('Please wait...');

        //  Check to see which modules can be udpated
        $updateList = $this->checkForUpdates($activeModules);
        $available  = $updateList->whereIn('can_update', true);

        //  If no updates are availabe, exit
        if($available->isEmpty())
        {
            $this->info('No Module upates are available');
            $this->line('Exiting.');

            return 0;
        }

        //  Give user options for which updates to install
        $this->info('You have updates available');
        $selected = $this->choice('Please select which Module to update', array_merge(['All'], $available->pluck('module_name')->toArray(), ['Exit']));

        if($selected === 'Exit')
        {
            $this->line('Exiting');
            return 0;
        }
        elseif($selected === 'All')
        {
            //  Update all modules
            foreach($available as $updateMe)
            {
                if($this->updateModule($updateMe))
                {
                    $this->info('Successfully Updated '.$updateMe['module_name']);
                }
            }
        }
        else
        {
            //  Update the selected module
            if($this->updateModule($available->whereIn('module_name', $selected)->first()))
            {
                $this->info('Successfully updated '.$selected);
            }
        }

        return 0;
    }

    protected function updateModule($moduleData)
    {
        $this->line('Updating '.$moduleData['module_name']);

        //  Get the requirements for the module to make sure they are met
        $moduleList = $this->getAvailableModules();
        $myModule   = $moduleList[$moduleData['alias']];

        // $failed = $this->checkJsonRequirements($myModule);
        if($failed = $this->checkJsonRequirements($myModule))
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

            return false;
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
        $this->updateCache();

        //  Cleanup leftover files
        $this->cleanupPackage($package);

        return true;
    }
}
