<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Models\AppSettings;
use App\Service\TimezoneList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BasicSettingsController extends Controller
{
    /**
     * Display the Application Settings.
     */
    public function show(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Settings', [
            'settings' => [
                'url' => preg_replace('(^https?://)', '', config('app.url')),
                'company_name' => config('app.company_name'),
                'timezone' => config('app.timezone'),
                'max_filesize' => (int) config('filesystems.max_filesize'),
            ],
            'timezone-list' => TimezoneList::Build(),
        ]);
    }

    /**
     * Update the Applications Settings
     */
    public function update(BasicSettingsRequest $request): RedirectResponse
    {
        $request->processSettings();

        Log::notice(
            'Application Configuration updated by '.$request->user()->username,
            $request->toArray()
        );

        return back()->with('success', __('admin.config.updated'));
    }
}
