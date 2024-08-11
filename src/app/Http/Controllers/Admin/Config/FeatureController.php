<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureConfigRequest;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FeatureController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('update', AppSettings::class);

        return Inertia::render('Admin/Config/Features', [
            'feature-list' => [
                'file_links' => (bool) config('fileLink.feature_enabled'),
                'public_tips' => (bool) config('techTips.allow_public'),
                'tip_comments' => (bool) config('techTips.allow_comments'),
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(FeatureConfigRequest $request)
    {
        $request->updateFeatureSettings();

        Log::info('Application Features updated by ' . $request->user()->username);

        return back()->with('success', 'Feature Settings Updated');
    }
}
