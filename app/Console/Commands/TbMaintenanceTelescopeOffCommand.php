<?php

namespace App\Console\Commands;

use App\Traits\AppSettingsTrait;
use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
class TbMaintenanceTelescopeOffCommand extends Command
{
    use AppSettingsTrait;

    protected $signature = 'tb_maintenance:telescope_off';
    protected $description = 'Command description';

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
        $this->saveSettings('telescope.enabled', false);
        $this->info('Telescope Advanced Data Collection turned off');

        return 0;
    }
}
