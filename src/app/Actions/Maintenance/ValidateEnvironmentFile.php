<?php

namespace App\Actions\Maintenance;

use App\Services\_Base\ApplicationEnvironment;
use Illuminate\Support\Facades\Log;

class ValidateEnvironmentFile extends ApplicationEnvironment
{
    /*
    |---------------------------------------------------------------------------
    | Verify that all necessary variables exist in the .env file.  This command
    | will write and update any missing variables.
    |---------------------------------------------------------------------------
    */
    public function __invoke()
    {
        $this->checkBaseUrl();
        $this->checkReverbVariables();

        $this->rebuildCache();
        $this->rebuildAppFiles();
    }

    /**
     * Check to see if the BASE_URL key exists.  Add if it is missing.  This
     * key was added in Version 7.0
     */
    protected function checkBaseUrl(): void
    {
        $baseUrl = $this->getEnvKeyValue('BASE_URL');

        if (! $baseUrl) {
            Log::notice(
                'BASE_URL key missing from Environment File.  Creating Key.'
            );

            // APP_URL holds the full URL of the Tech Bench
            $appUrl = $this->getEnvKeyValue('APP_URL');

            // Remove Protocol from URL.
            preg_match('/^(http|https):\/\/(.*)/', $appUrl, $splitUrl);

            $protocol = $splitUrl ? $splitUrl[1] : 'https';
            $url = $splitUrl ? end($splitUrl) : $appUrl;

            $this->writeNewEnvironmentFileWith('BASE_URL', $url);
            $this->writeNewEnvironmentFileReplacing(
                'APP_URL',
                '"'.$protocol.'://${BASE_URL}"'
            );
        }
    }

    /**
     * Check to make sure that the Reverb credentials exist.  Reverb added
     * in Version 7.0.
     */
    protected function checkReverbVariables(): void
    {
        $svc = new GenerateReverbCredentials;
        $svc();
    }
}
