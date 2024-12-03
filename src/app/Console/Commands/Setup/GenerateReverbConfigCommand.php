<?php

namespace App\Console\Commands\Setup;

use App\Actions\Maintenance\GenerateReverbCredentials;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class GenerateReverbConfigCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command
     */
    protected $signature = 'reverb:generate
                                {--force : Force the operation to run when in production}';

    /**
     * The console command description
     */
    protected $description = 'Generate new Credentials for Reverb App';

    /**
     * Execute the console command.
     */
    public function handle(GenerateReverbCredentials $svc)
    {
        // @codeCoverageIgnoreStart
        if (! $this->confirmToProceed()) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $this->line('Generating New Reverb Credentials');

        $svc();

        $this->info('New Credentials Created');
    }
}
