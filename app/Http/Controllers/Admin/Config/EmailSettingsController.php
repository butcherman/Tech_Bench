<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * View and adjust Email Settings
 */
class EmailSettingsController extends Controller
{
    public function get()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Email', [
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

    public function set(EmailSettingsRequest $request)
    {
        $request->processEmailSettings();

        Log::notice('Email Settings Updated by '.$request->user()->username, $request->except('password'));

        return back()->with('success', 'success');
    }
}
