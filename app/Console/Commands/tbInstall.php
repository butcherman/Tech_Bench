<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class tbInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tb-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new installation of the Tech Bench';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info('-----------------------------------------------------------------------------------');
        $this->info('|     ___________            .__      __________                     .__          |');
        $this->info('|     \__    ___/ ___   ____ |  |__   \______   \ ____   ____   ____ |  |__       |');
        $this->info('|        |    |_/ __ \_/ ___\|  |  \   |    |  _// __ \ /    \_/ ___\|  |  \      |');
        $this->info('|        |    |\  ___/\  \___|   Y  \  |    |   \  ___/|   |  \  \___|   Y  \     |');
        $this->info('|        |____| \___  >\___  >___|  /  |______  /\___  >___|  /\___  >___|  /     |');
        $this->info('|                   \/     \/     \/          \/     \/     \/     \/     \/      |');
        $this->info('-----------------------------------------------------------------------------------');
        $this->info('');
        $this->line('                     Welcom to the Tech Bench Installer');
        $this->line('');
    }
}
