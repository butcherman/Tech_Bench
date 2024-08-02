<?php

namespace App\Http\Controllers\TechTips;

use App\Http\Controllers\Controller;
use App\Http\Requests\TechTips\TechTipsSettingsRequest;
use App\Models\TechTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TechTipsSettingsController extends Controller
{
    /**
     * Show the form for editing the resource.
     */
    public function edit(Request $request)
    {
        $this->authorize('manage', TechTip::class);

        return Inertia::render('TechTips/Admin', [
            'settings' => [
                'allow_comments' => (bool) config('techTips.allow_comments'),
                'allow_public' => (bool) config('techTips.allow_public'),
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(TechTipsSettingsRequest $request)
    {
        $request->processSettings();

        Log::notice('Tech Tip Settings updated by '.$request->user()->username);

        return back()->with('success', __('tips.settings_updated'));
    }
}
