<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;

/**
 * @codeCoverageIgnore
 */
class TbModuleCheckCommand extends Command
{
    protected $signature   = 'tb_module:check';
    protected $description = 'Output the version and status of all installed modules';

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
        $this->line('Checking Module Status');

        $moduleData = Module::all();
        $tableData  = [];

        foreach($moduleData as $module)
        {
            $tableData[] = [
                $module->getName(),
                config($module->getLowerName().'.ver'),
                $module->isEnabled() ? 'Enabled' : 'Disabled',
            ];
        }

        $this->table(['Module Name', 'Version', 'Enabled'], $tableData);
        $this->line('Note:  Version information is not listed for Disabled Modules');

        return 0;
    }
}
