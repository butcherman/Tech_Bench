<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureConfigRequest;
use App\Models\AppSettings;
use App\Service\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FeatureController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Display the available features
     */
    public function show(): Response
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
     * Update the active features
     */
    public function update(FeatureConfigRequest $request): RedirectResponse
    {
        $this->svc->updateFeatureSettings($request);

        return back()->with('success', 'Feature Settings Updated');
    }
}
