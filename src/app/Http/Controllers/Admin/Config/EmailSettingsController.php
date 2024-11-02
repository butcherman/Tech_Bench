<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\EmailSettingsRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class EmailSettingsController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Email', [
            'settings' => $this->svc->getEmailSettings(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(EmailSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateEmailSettings($request->safe()->collect());

        Log::notice(
            'Email Settings Updated by '.$request->user()->username,
            $request->except('password')
        );

        return back()->with('success', __('admin.email.updated'));
    }
}
