<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingsRequest;
use Illuminate\Support\Facades\Log;

class EmailSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        //
        return 'show email settings controller';
    }

    /**
     * Update the resource in storage.
     */
    public function update(EmailSettingsRequest $request)
    {
        $request->processSettings();

        Log::notice('Email Settings Updated by '.$request->user()->username,
            $request->except('password'));

        return back()->with('success', __('admin.email.updated'));
    }
}
