<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogsIndexController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $channel = null)
    {
        return Inertia::render('Maint/LogsIndex', [
            'channels' => $this->logChannels,
            'levels' => $this->logLevels,
            'channel' => $channel,
            'log-list' => $channel ? $this->getChannelLogs($channel) : null,
        ]);
    }
}
