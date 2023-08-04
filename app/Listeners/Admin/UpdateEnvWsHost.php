<?php

namespace App\Listeners\Admin;

use App\Events\Admin\AppUrlChangedEvent;
use App\Jobs\Maintenance\CallViteBuild;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEnvWsHost implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(AppUrlChangedEvent $event): void
    {
        $oldHost = preg_replace('(^https?://)', '', $event->oldUrl);
        $newHost = preg_replace('(^https?://)', '', $event->newUrl);
        $envFile = base_path('.env');

        if (file_exists($envFile)) {
            file_put_contents($envFile, str_replace(
                'VITE_WS_HOST='.$oldHost, 'VITE_WS_HOST='.$newHost, file_get_contents($envFile)
            ));
        }

        dispatch(new CallViteBuild);
    }
}
