<?php

namespace App\Http\Controllers\Admin\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogSettingsRequest;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LogSettingsController extends Controller
{
    use LogUtilitiesTrait;

    public function get()
    {
        $this->authorize('viewAny', AppSettings::class);

        $logChannels = $this->getLogChannels(true);
        $channelValues = [];

        foreach ($logChannels as $channel) {
            if ($channel['name'] !== 'Emergency') {
                $channelValues[$channel['channel']] = ucfirst(config('logging.channels.'.strtolower($channel['channel']).'.level'));
            }
        }

        return Inertia::render('Admin/Logs/Settings', [
            'levels' => $this->getLogLevels()->pluck('name'),
            'channels' => $logChannels,
            'days' => (int) config('logging.days'),
            'values' => $channelValues,
        ]);
    }

    public function set(LogSettingsRequest $request)
    {
        $request->processRequest();

        Log::notice('Logging Configuration changed by '.$request->user()->username, $request->toArray());

        return back()->with('success', __('admin.logging.updated'));
    }
}
