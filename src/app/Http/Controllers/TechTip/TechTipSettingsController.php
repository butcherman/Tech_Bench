<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipSettingsRequest;
use App\Models\TechTip;
use App\Services\TechTip\TechTipAdministrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TechTipSettingsController extends Controller
{
    public function __construct(protected TechTipAdministrationService $svc) {}

    /**
     * Show form to Edit Tech Tip Settings.
     */
    public function edit(): Response
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render(
            'TechTip/Admin/Settings',
            $this->svc->getTechTipSettings()
        );
    }

    /**
     * Update the Tech Tip Settings.
     */
    public function update(TechTipSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateTechTipSettings($request->safe()->collect());

        return back()->with('success', __('tips.settings_updated'));
    }
}
