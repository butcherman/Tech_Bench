<?php

namespace App\Http\Controllers\Admin\Config;

use App\Actions\BuildTimezoneList;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppConfigRequest;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AppConfigController extends Controller
{
    public function get()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config', [
            'settings' => [
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'max_filesize' => config('filesystems.max_filesize'),
            ],
            'tz-list' => (new BuildTimezoneList)->build(),
        ]);
    }

    public function set(AppConfigRequest $request)
    {
        $request->processSettings();

        Log::notice('Application Configuration changed by '.$request->user()->username, $request->toArray());

        return back()->with('success', __('admin.config.updated'));
    }
}
