<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $fileArr = $this->getFileToArray($filename, $channel);

        return Inertia::render('Logs/Show', [
            'levels'   => $this->logLevels,
            'channel'  => $channel,
            'filename' => $filename,
            'stats'    => [$this->getFileStats($fileArr, $filename)],
            'log_file' => $this->parseFile($fileArr),
        ]);
    }
}
