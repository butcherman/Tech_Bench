<?php

// TODO - Refactor

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Service\Maint\LogUtilitiesService;
use Inertia\Inertia;

class LogsIndexController extends Controller
{
    /**
     * List all log channels and log files for the selected channel
     */
    public function __invoke(?string $channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);
        $logObj = new LogUtilitiesService;

        if ($channel) {
            $logObj->validateLogChannel($channel);
        }

        return Inertia::render('Maint/LogsIndex', [
            'channels' => $logObj->getLogChannels(),
            'levels' => $logObj->getLogLevels(),
            'channel' => $channel,
            'channel-type' => $channel ? $logObj->getChannelType($channel) : null,
            'log-list' => $channel ? $logObj->getLogList($channel) : [],
        ]);
    }
}
