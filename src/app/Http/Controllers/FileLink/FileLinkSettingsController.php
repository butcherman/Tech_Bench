<?php

namespace App\Http\Controllers\FileLink;

use App\Events\Feature\FeatureChangedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkSettingsRequest;
use App\Models\FileLink;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FileLinkSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Settings', [
            'settings' => [
                'default_link_life' => config('fileLink.default_link_life'),
                'auto_delete' => (bool) config('fileLink.auto_delete'),
                'auto_delete_days' => config('fileLink.auto_delete_days'),
                'auto_delete_override' => (bool) config('fileLink.auto_delete_override'),
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(FileLinkSettingsRequest $request)
    {
        $request->updateFileLinkSettings();

        Log::info('File Link Settings updated by '.$request->user()->username, $request->toArray());
        event(new FeatureChangedEvent);

        return back()->with('success', 'File Link Settings Updated');
    }
}
