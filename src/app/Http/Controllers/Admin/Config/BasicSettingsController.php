<?php

namespace App\Http\Controllers\Admin\Config;


use App\Actions\Misc\BuildTimezoneList;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\BasicSettingsRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BasicSettingsController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Show the form for editing the applications basic settings.
     */
    public function edit(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Settings', [
            'settings' => fn() => $this->svc->getBasicSettings(),
            'timezone-list' => fn() => BuildTimezoneList::build(),
        ]);
    }

    /**
     * Update the basic settings.
     */
    public function update(BasicSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateBasicSettings($request->safe()->collect());

        Log::notice(
            'Application Configuration updated by ' . $request->user()->username,
            $request->toArray()
        );

        return back()->with('success', __('admin.config.updated'));
    }
}
