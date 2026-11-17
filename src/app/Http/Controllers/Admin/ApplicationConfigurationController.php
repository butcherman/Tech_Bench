<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Misc\BuildTimezoneList;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApplicationConfigurationRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationConfigurationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationConfigurationController extends Controller
{
    public function __construct(protected ApplicationConfigurationService $svc) {}

    public function edit(): Response
    {
        $this->authorize('update', AppSettings::class);

        return Inertia::render('Admin/Config/Settings', [
            'settings' => fn () => $this->svc->getConfigSettings(),
            'timezone-list' => fn () => BuildTimezoneList::build(),
        ]);
    }

    public function update(ApplicationConfigurationRequest $request): RedirectResponse
    {
        $this->svc->updateBasicSettings($request->safe()->collect());

        return back()->with('success', __('admin.config.updated'));
    }
}
