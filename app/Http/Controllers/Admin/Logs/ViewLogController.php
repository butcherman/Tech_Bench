<?php

namespace App\Http\Controllers\Admin\Logs;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Traits\LogUtilitiesTrait;
use App\Models\AppSettings;

class ViewLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Show the selected log file
     */
    public function __invoke($channel, $file)
    {
        $this->authorize('viewAny', AppSettings::class);
        $this->validateChannel($channel);

        $fileArr = $this->getFileToArray($file, $this->getChannelDetails($channel));

        return Inertia::render('Admin/Logs/Show', [
            'levels'   => $this->logLevels,
            'channel'  => $channel,
            'filename' => $file,
            'stats'    => $this->getFileStats($fileArr, $file),
            'log-file' => $this->parseFile($fileArr),
        ]);
    }
}
