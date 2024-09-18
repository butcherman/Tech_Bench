<?php

namespace App\Listeners\Config;

use App\Events\Admin\AdministrationEvent;
use App\Events\Config\UrlChangedEvent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class UrlChangedListener
{
    protected $envPath;

    public function __construct()
    {
        $this->envPath = base_path('.env');
    }

    /**
     * Handle the event.
     */
    public function handle(UrlChangedEvent $event): void
    {
        event(new AdministrationEvent('Writing New URL'));

        // Write new URL to the .env file so Vite can build properly
        if (file_exists($this->envPath)) {
            Log::debug('Updating .env file with new URL');
            file_put_contents(
                $this->envPath,
                str_replace(
                    'BASE_URL='.$event->oldUrl,
                    'BASE_URL='.$event->newUrl,
                    file_get_contents($this->envPath)
                )
            );
        }

        // @codeCoverageIgnoreStart
        if (App::environment('production')) {
            // Clear and re-cache all config data
            event(new AdministrationEvent('Building Application Cache'));
            Log::debug('Re-Caching application configuration');
            Artisan::call('optimize:clear');
            Process::run('php /app/artisan breadcrumbs:cache');
            Artisan::call('optimize');

            // Rebuild all JS application files
            event(new AdministrationEvent('Building Application Files'));
            Log::debug('Rebuilding Application Files');
            Process::run('npm run build');
            event(new AdministrationEvent('Build Complete'));
        }
        // @codeCoverageIgnoreEnd
    }
}
