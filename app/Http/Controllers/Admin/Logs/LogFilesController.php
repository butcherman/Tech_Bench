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

        $props = [];
        if(!is_null($channel))
        {
            //  If the channel name is invalid, return 404 error
            if(is_null($this->getChannelDetails($channel)))
            {
                abort(404, 'Cannot find the specified Log Channel');
            }

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
