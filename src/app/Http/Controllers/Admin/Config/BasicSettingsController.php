<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Models\AppSettings;
use App\Service\Admin\ApplicationSettingsService;
use App\Service\TimezoneList;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BasicSettingsController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

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
     * Update the Application Settings
     */
    public function update(BasicSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateBasicSettings($request);

        return back()->with('success', __('admin.config.updated'));
    }
}
