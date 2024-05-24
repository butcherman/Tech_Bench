<?php

namespace App\Http\Controllers\Maintenance;

use App\Enum\LogChannel;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogsIndexController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);
        if ($channel) {
            $this->validateChannel($channel);
        }

        return Inertia::render('Maint/LogsIndex', [
            'channels' => $this->logChannels,
            'levels' => $this->logLevels,
            'channel' => $channel,
            'log-list' => $channel ? $this->getChannelLogs($channel) : null,
        ]);
    }
}
