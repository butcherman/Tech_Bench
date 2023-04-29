<?php

namespace App\Console\Commands;

use App\Traits\AppSettingsTrait;
use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
class TbMaintenanceTelescopeOnCommand extends Command
{
    use AppSettingsTrait;

    protected $signature = 'tb_maintenance:telescope_on';

    protected $description = 'Enable the Telescope advanced data collection service';

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
        $this->saveSettings('telescope.enabled', true);
        $this->info('Telescope Advanced Data Collection turned on');

        return 0;
    }
}
