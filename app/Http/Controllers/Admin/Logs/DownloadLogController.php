<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadLogController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Download a log file
     */
    public function __invoke($channel, $file)
    {
        $this->authorize('viewAny', AppSettings::class);
        $this->validateChannel($channel);
        $this->validateLogFile($file, $this->getChannelDetails($channel));

        Log::info('User '.Auth::user()->username.' downloaded log file', [
            'channel' => $channel,
            'file' => $file,
        ]);

        return Storage::disk('logs')->download($channel.DIRECTORY_SEPARATOR.$file.'.log');
    }
}
