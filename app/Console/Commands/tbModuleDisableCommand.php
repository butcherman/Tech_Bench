<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;

/**
 * @codeCoverageIgnore
 */
class tbModuleDisableCommand extends Command
{
    protected $signature = 'tb_module:disable {module} {--y|yes}';

    protected $description = 'Disable a previously installed module';

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
        Log::notice('Running Command tb_module:disable for Module '.$this->argument('module'));

        $module = Module::find($this->argument('module'));

        //  Verify that the module files actually exist
        if (! $module) {
            $this->error('Unable to find module specified.');
            $this->error('Please make sure that the files are loaded and the name is spelled correctly');

            return 0;
        }

        //  Have user verify that they want to disable the module
        if ($this->option('yes')) {
            $confirm = true;
        } else {
            $this->line('You are about to disable Module '.$module->getName());
            $confirm = $this->confirm('Are you sure?');
        }

        if (! $confirm) {
            $this->info('Exiting');

            return 0;
        }

        $module->disable();

        $this->info('Module has been disabled');
        $this->line('Module still exists and can be re-activated later');
        $this->line('To completely remove the Module, run the command "php artisan tb_module:delete '.$module->getStudlyName().'"');

        return 0;
    }
}
