<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ViewLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $channel, string $logFile)
    {
        $fileArr = $this->getFileToArray($channel.DIRECTORY_SEPARATOR.$logFile.'.log');

        return Inertia::render('Admin/Logs/Show', [
            'levels' => $this->getLogLevels(),
            'channel' => $channel,
            'file-stats' => $this->getFileStats($fileArr),
            'log-file' => $this->parseLogFile($fileArr),
        ]);
    }
}
