<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Inertia\Inertia;

/**
 * List all log channels and log files that exist in the system
 */
class LogsController extends Controller
{
    use LogUtilitiesTrait;

    public function __invoke(string $channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Logs/Index', [
            'channels' => $this->getLogChannels(),
            'levels' => $this->getLogLevels(),
            'channel' => $channel,
            'log-list' => $channel ? $this->getLogList($channel) : null,
        ]);
    }
}
