<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DownloadLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $channel, string $file)
    {
        $this->authorize('viewAny', AppSettings::class);

        //  Validate log file exists
        if(!$this->validateLogFile($channel, $file)) {
            abort(404, 'Cannot find the specified Log File');
            Log::error($request->user()->username.' has requested an invalid Log File '.$channel.DIRECTORY_SEPARATOR.$file);
        }

        Log::info($request->user()->username.' is downloading Log File '.$channel.DIRECTORY_SEPARATOR.$file);

        return Storage::disk('logs')->download($channel.DIRECTORY_SEPARATOR.$file.'.log');
    }
}
