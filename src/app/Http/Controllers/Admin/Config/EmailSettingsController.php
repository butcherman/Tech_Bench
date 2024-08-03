<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Models\AppSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class EmailSettingsController extends Controller
{
    /**
     * Display the Email Settings.
     */
    public function show(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Email', [
            'settings' => [
                'from_address' => config('mail.from.address'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
                'username' => config('mail.mailers.smtp.username'),
                'password' => config('mail.mailers.smtp.password') ? __('admin.fake_password') : '',
                'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
            ],
        ]);
    }

    /**
     * Update the Email Settings
     */
    public function update(EmailSettingsRequest $request): RedirectResponse
    {
        $request->processSettings();

        Log::notice(
            'Email Settings Updated by '.$request->user()->username,
            $request->except('password')
        );

        return back()->with('success', __('admin.email.updated'));
    }
}
