<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\FeatureConfigRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class FeatureController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit(): Response
    {
        $this->authorize('update', AppSettings::class);

        return Inertia::render('Admin/Config/Features', [
            'feature-list' => [
                'file_links' => config('file-link.feature_enabled'),
                'public_tips' => config('tech-tips.allow_public'),
                'tip_comments' => config('tech-tips.allow_comments'),
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(FeatureConfigRequest $request): RedirectResponse
    {
        $this->svc->updateFeatureSettings($request->safe()->collect());

        Log::info('Application Features updated by '.$request->user()->username);

        return back()->with('success', 'Feature Settings Updated');
    }
}
