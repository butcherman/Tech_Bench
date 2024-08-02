<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DownloadLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $channel, string $logFile)
    {
        $this->authorize('viewAny', AppSettings::class);
        $this->validateChannel($channel);

        $filePath = $this->getLogFile($channel, $logFile);

        Log::info($request->user()->username.' is downloading log file '.$filePath);

        return $this->storage->download($filePath);
    }
}
