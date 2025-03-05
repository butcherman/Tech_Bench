<?php

namespace App\Listeners\Config;

use App\Actions\Admin\UpdateApplicationUrl;
use App\Events\Config\UrlChangedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleUrlChangeListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected UpdateApplicationUrl $svc) {}

    /**
     * Handle the event.
     */
    public function handle(UrlChangedEvent $event): void
    {
        Log::alert(
            config('app.name') . ' URL has changed.  Rebuilding application files',
            [
                'old-url' => $event->oldUrl,
                'new-url' => $event->newUrl,
            ]
        );

        $this->svc->handle($event->newUrl);
    }
}
