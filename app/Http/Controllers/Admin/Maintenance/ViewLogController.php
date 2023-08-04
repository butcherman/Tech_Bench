<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Exceptions\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Parse and display a log file
 */
class ViewLogController extends Controller
{
    use LogUtilitiesTrait;

    public function __invoke(Request $request, string $channel, string $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);

        //  Validate log file exists
        if (! $this->validateLogFile($channel, $logFile)) {
            throw new LogFileMissingException($logFile);
        }

        $fileArr = $this->getFileToArray($channel.DIRECTORY_SEPARATOR.$logFile.'.log');

        return Inertia::render('Admin/Logs/Show', [
            'levels' => $this->getLogLevels(),
            'channel' => $channel,
            'file-stats' => $this->getFileStats($fileArr),
            'log-file' => $this->parseLogFile($fileArr),
            'file-name' => $logFile,
        ]);
    }
}
