<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class LogFilesController extends Controller
{
    use LogUtilitiesTrait;

    /**
     *  View a list of log files
     */
    public function __invoke($channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);

        $props = [];

        if(!is_null($channel))
        {
            $props = [
                'channel'   => $channel,
                'log_files' => $this->getChannelStats($channel),
                'levels'    => $this->logLevels,
            ];
        }

        $props['channels'] = Arr::pluck($this->logChannels, 'name');

        return Inertia::render('Logs/Index', $props);
    }
}
