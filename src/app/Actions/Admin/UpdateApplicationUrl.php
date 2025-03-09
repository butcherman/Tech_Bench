<?php

namespace App\Actions\Admin;

use App\Services\_Base\ApplicationEnvironment;
use Illuminate\Support\Facades\Log;

class UpdateApplicationUrl extends ApplicationEnvironment
{
    /*
    |---------------------------------------------------------------------------
    | Update the URL in the .env file, then re-cache configuration, and run
    | npm build to rebuild all JS files with proper URL.
    |---------------------------------------------------------------------------
    */
    public function handle(string $url): void
    {
        Log::debug('URL Changed, updating .env file with new URL');

        $this->writeNewEnvironmentFileReplacing('BASE_URL', $url);

        Log::debug('Calling Rebuild Cache');

        $this->rebuildCache();

        Log::debug('Calling Rebuild App Files');

        $this->rebuildAppFiles();
    }
}
