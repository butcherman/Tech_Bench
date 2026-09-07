<?php

namespace App\Actions\Maintenance;

use App\Services\_Base\ApplicationEnvironment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ValidateEnvFile extends ApplicationEnvironment
{
    /**
     * Validate the the .env file has all of the necessary entries for the
     * current Tech Bench version.
     */
    public function __invoke(): void
    {
        $this->checkBaseUrl();
        $this->checkReverbVariables();
    }

    /**
     * Check to see if the BASE_URL key exists
     * If it does not, refactor to add
     */
    protected function checkBaseUrl(): void
    {
        $baseUrl = $this->getEnvKeyValue('BASE_URL');
        Log::debug('Checking Base URL');

        if (! $baseUrl) {
            $appUrl = $this->getEnvKeyValue('APP_URL');

            preg_match('/^(http|https):\/\/(.*)/', $appUrl, $splitUrl);

            $protocol = $splitUrl ? $splitUrl[1] : 'https';
            $url = $splitUrl ? end($splitUrl) : $appUrl;

            $this->writeNewEnvironmentFileWith('BASE_URL', $url);
            $this->writeNewEnvironmentFileReplacing(
                'APP_URL',
                '"'.$protocol.'://${BASE_URL}"'
            );

            Log::notice('Base URL Missing, ENV File updated', [
                'BASE_URL' => $url,
                'APP_URL' => '"'.$protocol.'://${BASE_URL}"',
            ]);
        }
    }

    /**
     * Check to make sure that the Reverb credentials exist
     */
    protected function checkReverbVariables(): void
    {
        Log::debug('Checking Reverb Variables');

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

        // Verify Reverb Credential Keys
        foreach ($passKeyList as $key) {
            if (! $this->getEnvKeyValue($key)) {
                $this->writeNewEnvironmentFileWith($key, Str::ulid());
                Log::notice('Created new Reverb Credential', [
                    'key' => $key,
                    'value' => '[REDACTED]',
                ]);
            }
        }

        // Verify other Reverb Keys
        foreach ($otherKeys as $key => $value) {
            if (! $this->getEnvKeyValue($key)) {
                $this->writeNewEnvironmentFileWith($key, $value);
                Log::notice('Created new Reverb Credential', [
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }
}
