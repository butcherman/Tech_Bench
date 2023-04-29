<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class LogChannelsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Show the list of available log files
     */
    public function __invoke($channel)
    {
        $this->authorize('viewAny', AppSettings::class);
        $this->validateChannel($channel);

        return Inertia::render('Admin/Logs/Index', [
            'channels' => Arr::pluck($this->logChannels, 'name'),
            'log-files' => $this->getChannelStats($channel),
            'levels' => $this->logLevels,
        ]);
    }
}
