<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BasicSettingsRequest;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Log;

class BasicSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);

        return 'show admin basic settings controller';
    }

    /**
     * Update the resource in storage.
     */
    public function update(BasicSettingsRequest $request)
    {
        $request->processSettings();

        Log::notice('Application Configuration updated by '.$request->user()->username,
            $request->toArray());

        return back()->with('success', __('admin.config.updated'));
    }
}
