<?php

namespace App\Console\Commands;

use Nwidart\Modules\Facades\Module;
use PragmaRX\Version\Package\Version;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $this->newLine(2);

        Log::notice('Activate Module Command running for './** @scrutinizer ignore-type */$this->argument('module'));

        //  Verify that the module is not already installed
        $activeModules = Module::allEnabled();
        foreach($activeModules as $module)
        {
            if($module->getName() === $this->argument('module'));
            {
                $this->error('The '.$this->argument('module').' is already active');
                Log::critical('The '.$this->argument('module').' is already active');
                return 1;
            }
        }

        $this->line('Activating Module '.$this->argument('module'));
        $this->newLine(2);

        //  Verify that the module exists and is valid
        $module = $this->findModule($this->argument('module'));
        if(!$module)
        {
            $this->error('Unable to find module - '.$this->argument('module'));
            $this->error('Please make sure that this is a proper Tech Bench Module and loaded into the Modules directory');
            Log::critical('Unable to find module - '.$this->argument('module'));
            return 1;
        }

        $this->line('Found Module.  Checking Prerequisites');

        //  Verify that the correct version of Tech Bench is running for this module
        if(!$this->checkPrerequisites($module->getRequires()))
        {
            $this->error('Tech Bench does not meet the prerequisites for installing this module');
            $this->error('Please install the missing requirements and try again');
            Log::critical('Tech Bench does not meet the prerequisites for installing this module');
            Log::critical('Please install the missing requirements and try again');
            return 1;
        }

        $this->line('Prerequisites passed.  Activating Module');

        //  Enable the module
        $this->call('module:enable', ['module' => $module->getStudlyName()]);

        //  Run any migrations
        $this->line('Setting up database');
        $this->call('module:migrate', ['module' => $module->getStudlyName()]);

        //  Install NPM and Composer packages
        $this->line('Copying Files');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && composer install --no-dev --no-interaction --optimize-autoloader');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && npm install --silent --only=production');

        //  Combine the new Javascript files
        $this->line('Creating Javascript files (this may take some time)');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && npm run production');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.' && npm run production');

        $this->info('Module has been activated');
        Log::notice('Module '.$this->argument('module').' has been activated');

        return Command::SUCCESS;
    }

    /**
     * Verify that the Module Files are in place and that this is a Tech Bench Module
     */
    protected function findModule($name)
    {
        $basePath = $name;

        //  Verify that the module files exists
        $moduleData = Storage::disk('modules')->allFiles($basePath);
        if(empty($moduleData))
        {
            Log::critical('No data returned while searching for folder '.$name);
            return false;
        }

        $module   = Module::find($name);
        $require  = $module->getRequires();
        $config   = Storage::disk('modules')->get($basePath.'/Config/config.php');

        if(!$module || !$config || !isset($require['Tech_Bench']))
        {
            Log::critical('Key files missing from the Module Directory', [
                'module_file' => $module,
                'config_file' => $config,
            ]);
            return false;
        }

        return $module;
    }

    /**
     * Verify the current version of Tech Bench meets the minimum requirements, and any other required modules are installed
     */
    protected function checkPrerequisites($requires)
    {
        $failed = false;

        //  Verify version
        $curVersion = (new Version)->compact();
        if($curVersion < $requires['Tech_Bench'])
        {
            $failed = true;
            $this->error('Tech Bench must be running Version '.$requires['Tech_Bench'].' or higher to use this module');
            $this->newLine();
            Log::critical('Tech Bench must be running Version '.$requires['Tech_Bench'].' or higher to use this module');
        }

        //  Check for other required modules
        if(isset($requires['Modules']) && count($requires['Modules']) > 0)
        {
            foreach($requires['Modules'] as $req)
            {
                $found = Module::find($req);
                if(!$found)
                {
                    $failed = true;
                    $this->error('The '.$req.' module is missing and must be installed to use this module');
                    Log::critical('The '.$req.'module is missing and must be installed to use this module');
                }
            }
        }

        return !$failed;
    }
}
