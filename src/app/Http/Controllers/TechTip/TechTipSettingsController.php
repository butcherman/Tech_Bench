<?php

namespace App\Http\Controllers\TechTip;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTip\TechTipSettingsRequest;
use App\Models\TechTip;
use App\TechTip\TechTipAdministrationService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipSettingsController extends Controller
{
    public function __construct(protected TechTipAdministrationService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Admin', [
            'settings' => [
                'allow_comments' => (bool) config('tech-tips.allow_comments'),
                'allow_public' => (bool) config('tech-tips.allow_public'),
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(TechTipSettingsRequest $request)
    {
        $this->svc->updateTechTipSettings($request->safe()->collect());

        Log::notice('Tech Tip Settings updated by '.$request->user()->username);

        return back()->with('success', __('tips.settings_updated'));
    }
}
