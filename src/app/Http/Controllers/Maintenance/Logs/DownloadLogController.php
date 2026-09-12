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
    public function __invoke(Request $request, string $logFile): StreamedResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        if (! $this->svc->validateLogFile($logFile)) {
            throw new LogFileMissingException($logFile);
        }

        $filePath = 'Application/'.$logFile.'.log';

        Log::info($request->user()->username.' is downloading log file '.$filePath);

        return Storage::disk('logs')->download($filePath);
    }
}
