<?php

namespace App\Http\Controllers\Admin\Logs;

use Inertia\Inertia;

use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use App\Http\Controllers\Controller;

class ViewLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Show the log file
     */
    public function __invoke($channel, $filename)
    {
        $this->authorize('viewAny', AppSettings::class);

        $channel = $this->getChannelDetails($channel);
        if(!$channel)
        {
            abort(404, 'Invalid Log Channel supplied');
        }

        $fileArr = $this->getFileToArray($filename, $channel);
        if(!$fileArr)
        {
            abort(404, 'Unable to find the log file specified');
        }

        return Inertia::render('Logs/Show', [
            'levels'   => $this->logLevels,
            'channel'  => $channel,
            'filename' => $filename,
            'stats'    => [$this->getFileStats($fileArr, $filename)],
            'log_file' => $this->parseFile($fileArr),
        ]);
    }
}
