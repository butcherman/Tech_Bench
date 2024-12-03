<?php

namespace App\Console\Commands\Setup;

use App\Actions\Maintenance\ValidateEnvironmentFile;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class ValidateEnvFileCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:validate-env
                                {--force : Force the operation to run when in production}';

    /**
     * The console command description
     */
    protected $description = 'Verify that all necessary variables are assigned in .env file';

    /**
     * The contents of the .env file
     */
    protected $input;

    /**
     * Execute the console command.
     */
    public function handle(ValidateEnvironmentFile $svc): void
    {
        $this->line('Checking .env file');

        $svc();

        $this->components->success('.env file is up to date');
    }
}
