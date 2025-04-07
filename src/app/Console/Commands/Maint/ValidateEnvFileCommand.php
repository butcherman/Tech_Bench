<?php

namespace App\Console\Commands\Maint;

use App\Actions\Maintenance\ValidateEnvFile;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class ValidateEnvFileCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:validate-env
                                {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify that all necessary variables are assigned in .env file';

    /**
     * Execute the console command.
     */
    public function handle(ValidateEnvFile $svc): void
    {
        // @codeCoverageIgnoreStart
        if (! $this->confirmToProceed()) {

            return;
        }
        // @codeCoverageIgnoreEnd

        $this->line('Checking Environment File');

        $svc();

        $this->info('Environment File is up to date');
    }
}
