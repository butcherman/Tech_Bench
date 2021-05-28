<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActivateModuleCommand extends Command
{
    protected $signature   = 'tb_module:activate {module}';
    protected $description = 'Activate a new Tech Bench Add On Module for use';


    /**
     *  Activate the new Module
     */
    public function handle()
    {
        //  Name of the Module Folder
        $module = $this->argument('module');

        $this->line('Activating Module '.$module);

        if(file_exists(base_path().'/Modules/'.$module.'/Config/config.php'))
        {
            //  Create a symbolic link to the module
            $this->laravel->make('files')->link(base_path().'/Modules/'.$module.'/resources/js', base_path().'/resources/js/Modules/'.$module);
            $this->info('The Module '.$module.' has been successfully activated');
        }
        else
        {
            $this->error('Unable to find the '.$module.' Module.  Please verify it is loaded in the correct folder');
        }

        return 0;
    }
}
