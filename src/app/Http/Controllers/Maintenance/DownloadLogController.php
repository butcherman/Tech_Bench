<?php

// TODO - Refactor

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Service\Maint\LogParsingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadLogController extends Controller
{
    /**
     * Download a raw log file
     */
    public function __invoke(Request $request, string $channel, string $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);
        $logObj = new LogParsingService;
        $logObj->validateLogFile($channel, $logFile);

        $filePath = $channel.DIRECTORY_SEPARATOR.$logFile.'.log';
        Log::info($request->user()->username.' is downloading log file '.$filePath);

        return Storage::disk('logs')->download($filePath);
    }
}
