<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Inertia\Inertia;
use Inertia\Response;

class LogsIndexController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Show a listing of Log Channels and Logs in that Channel
     */
    public function __invoke(?string $channel = null): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/LogIndex', [
            'channels' => $this->svc->getLogChannels(),
            'channel' => $channel,
            'log-list' => $channel ? $this->svc->getLogList($channel) : [],
        ]);
    }
}
