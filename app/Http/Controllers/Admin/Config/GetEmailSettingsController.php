<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetEmailSettingsController extends Controller
{
    /**
     * Modify the email settings
     */
    public function __invoke(Request $request)
    {
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
