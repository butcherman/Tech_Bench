<?php

namespace App\Http\Controllers\Admin\Logs;

use Inertia\Inertia;

use Illuminate\Support\Arr;

use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use App\Http\Controllers\Controller;

class LogFilesController extends Controller
{
    use LogUtilitiesTrait;

    /**
     *  View a list of log files
     */
    public function __invoke($channel = null)
    {
        $this->authorize('viewAny', AppSettings::class);

        // dd($channel);

        $props = [];
        if(!is_null($channel))
        {
            //  TODO - Verify that the channel name is valid

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
