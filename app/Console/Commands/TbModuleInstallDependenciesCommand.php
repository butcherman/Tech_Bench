<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Nwidart\Modules\Facades\Module;

/**
 * @codeCoverageIgnore
 */
class TbModuleInstallDependenciesCommand extends Command
{
    protected $signature = 'tb_module:dependencies {moduleName?}';

    protected $description = 'Command description';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Install NPM dependencies required for the modules in the root node_modules directory
     */
    public function handle()
    {
        $this->line('Installing Module Dependencies');
        if ($this->argument('moduleName')) {
            $module = Module::find($this->argument('moduleName'));

            if (! $module) {
                $this->error('Unable to find module '.$this->argument('moduleName'));

                return 0;
            }

            $this->install($module);
        } else {
            $activeModules = Module::allEnabled();

            foreach ($activeModules as $module) {
                $this->install($module);
            }
        }

        return 0;
    }

    /**
     * Get the required npm packages and install them in the npm root node_modules
     */
    protected function install($module)
    {
        $this->line('Installing NPM dependencies for '.$module->getName());
        $requires = $module->getRequires();

        if (Arr::exists($requires, 'npm')) {
            foreach ($requires['npm'] as $r) {
                shell_exec('cd '.base_path().' && npm install '.$r);
            }
        }
    }
}
