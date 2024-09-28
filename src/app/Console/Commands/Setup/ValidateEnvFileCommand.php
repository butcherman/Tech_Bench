<?php

namespace App\Console\Commands\Setup;

use App\Traits\EnvironmentFileTrait;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class ValidateEnvFileCommand extends Command
{
    use ConfirmableTrait;
    use EnvironmentFileTrait;

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
    public function handle()
    {
        $this->line('Checking .env file');

        $this->checkBaseUrl();
        $this->checkReverbVariables();

        $this->cacheConfig();
        $this->components->success('.env file is up to date');
    }

    /**
     * Check to see if the BASE_URL key exists
     * If it does not, refactor to add
     */
    protected function checkBaseUrl()
    {
        $baseUrl = $this->getEnvKeyValue('BASE_URL');

        if (! $baseUrl) {
            $appUrl = $this->getEnvKeyValue('APP_URL');

            preg_match('/^(http|https):\/\/(.*)/', $appUrl, $splitUrl);

            $protocol = $splitUrl ? $splitUrl[1] : 'https';
            $url = $splitUrl ? end($splitUrl) : $appUrl;

            if (! $this->writeNewEnvironmentFileWith('BASE_URL', $url)) {
                $this->components->error('Unable to write new BASE_URL variable');

                return;
            }

            $this->writeNewEnvironmentFileReplacing('APP_URL', '"'.$protocol.'://${BASE_URL}"');
            $this->components->info('BASE_URL key generated successfully');
        }
    }

    /**
     * Check to make sure that the Reverb credentials exist
     */
    protected function checkReverbVariables()
    {
        $success = true;
        $changed = false;
        $passKeyList = [
            'REVERB_APP_ID',
            'REVERB_APP_KEY',
            'REVERB_APP_SECRET',
        ];

        $otherKeys = [
            'REVERB_HOST' => '"${BASE_URL}"',
            'VITE_REVERB_APP_KEY' => '"${REVERB_APP_KEY}"',
            'VITE_REVERB_HOST' => '"${REVERB_HOST}"',
        ];

        foreach ($passKeyList as $key) {
            if (! $this->getEnvKeyValue($key)) {
                $passed = $this->writeNewEnvironmentFileWith($key, Str::ulid());
                $changed = true;
                if (! $passed) {
                    $this->components->error('Unable to set .env variable for '.$key.'. An unexpected error has occurred.');
                    $success = false;
                } else {
                    $this->components->info($key.' created successfully');
                }
            }
        }

        foreach ($otherKeys as $key => $value) {
            if (! $this->getEnvKeyValue($key)) {
                $passed = $this->writeNewEnvironmentFileWith($key, $value);
                $changed = true;
                if (! $passed) {
                    $this->components->error('Unable to set .env variable for '.$key.'. An unexpected error has occurred.');
                    $success = false;
                } else {
                    $this->components->info($key.' created successfully');
                }
            }
        }

        $this->newLine();
        if ($changed) {
            if ($success) {
                $this->components->info('Reverb credentials created successfully.');
            } else {
                $this->components->error('One or more errors occurred.  Please check errors above.');
            }
        }
    }
}
