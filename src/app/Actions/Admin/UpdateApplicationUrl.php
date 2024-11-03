<?php

namespace App\Actions\Admin;

use App\Services\_Base\ApplicationEnvironment;

class UpdateApplicationUrl extends ApplicationEnvironment
{
    /**
     * Update the URL in the .env file, then re-cache and rebuild all
     * application files
     */
    public function handle(string $url): void
    {
        $this->writeNewEnvironmentFileReplacing('BASE_URL', $url);

        $this->rebuildCache();
        $this->rebuildAppFiles();
    }
}
