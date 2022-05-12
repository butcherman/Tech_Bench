<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadLogController extends Controller
{
    /**
     * Download a raw log file
     */
    public function __invoke($channel, $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);

        if(Storage::disk('logs')->missing($channel.DIRECTORY_SEPARATOR.$logFile.'.log'))
        {
            abort(404, 'Unable to find log file');
        }

        return Storage::disk('logs')->download($channel.DIRECTORY_SEPARATOR.$logFile.'.log');
    }
}
