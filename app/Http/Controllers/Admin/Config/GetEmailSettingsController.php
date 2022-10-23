<?php

namespace App\Http\Controllers\Admin\Config;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;

class GetEmailSettingsController extends Controller
{
    /**
     * Modify the email settings
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Email', [
            'settings' => [
                'host'         => config('mail.mailers.smtp.host'),
                'port'         => config('mail.mailers.smtp.port'),
                'encryption'   => strtoupper(config('mail.mailers.smtp.encryption')),
                'username'     => config('mail.mailers.smtp.username'),
                'password'     => config('mail.mailers.smtp.password') ? __('admin.fake_password') : '',
                'from_address' => config('mail.from.address'),
                'requireAuth'  => (bool) config('mail.mailers.smtp.require_auth'),
            ],
        ]);
    }
}
