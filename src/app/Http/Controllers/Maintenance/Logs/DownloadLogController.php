<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Exceptions\Maintenance\LogFileMissingException;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadLogController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Download a raw log file
     */
    public function __invoke(Request $request, string $channel, string $logFile): StreamedResponse
    {
        // TODO - Give logs their own policy
        $this->authorize('viewAny', AppSettings::class);

        $filePath = $this->svc->validateLogFile($channel, $logFile);

        if (!$filePath) {
            throw new LogFileMissingException($logFile);
        }

        Log::info($request->user()->username . ' is downloading log file ' . $filePath);

        return Storage::disk('logs')->download($filePath);
    }
}
