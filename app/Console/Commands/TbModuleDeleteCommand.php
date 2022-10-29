<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;

use App\Traits\ModuleTrait;

/**
 * @codeCoverageIgnore
 */
class TbModuleDeleteCommand extends Command
{
    use ModuleTrait;

    protected $signature   = 'tb_module:delete {module} {--y|yes}';
    protected $description = 'Completely remove a Module from the Tech Bench';

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
        Log::notice('Running Command tb_module:delete for Module '.$this->argument('module'));

        $module = Module::find($this->argument('module'));

        //  Verify that the module files actually exist
        if(!$module)
        {
            $this->error('Unable to find module specified.');
            $this->error('Please make sure that the files are loaded and the name is spelled correctly');
            return 0;
        }

        //  Have user verify that they want to disable the module
        if($this->option('yes'))
        {
            $confirm = true;
        }
        else
        {
            $this->line('You are about to delete Module '.$module->getName());
            $this->line('IMPORTANT:  THIS CANNOT BE UNDONE');
            $confirm = $this->confirm('Are you sure?');
        }

        if(!$confirm)
        {
            $this->info('Exiting');
            return 0;
        }

        $this->line('Deleteing Module');
        $this->line('Please Wait');

        //  If the module was disabled, we need to enable it to access its configuration
        if($module->isDisabled())
        {
            $module->enable();
        }

        //  Remove from database
        $this->rollbackMigrations($module);
        //  Remove all files
        $this->destroyModuleDisk($module);

        $module->delete();

        //  Rebuild javascript files
        $this->runProduction();
        $this->updateCache();

        $this->info('Module has been deleted');

        return 0;
    }
}
