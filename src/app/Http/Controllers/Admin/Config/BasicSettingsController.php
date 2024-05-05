<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Models\AppSettings;
use App\Service\TimezoneList;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BasicSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Settings', [
            'settings' => [
                'url' => preg_replace('(^https?://)', '', config('app.url')),
                'timezone' => config('app.timezone'),
                'max_filesize' => (int) config('filesystems.max_filesize'),
            ],
            'timezone-list' => TimezoneList::Build(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(BasicSettingsRequest $request)
    {
        $request->processSettings();

        Log::notice(
            'Application Configuration updated by ' . $request->user()->username,
            $request->toArray()
        );

        return back()->with('success', __('admin.config.updated'));
    }
}
