<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Inertia\Inertia;

class StepThree extends Controller
{
    /**
     * Email settings for first time setup
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Init/StepThree', [
            'settings' => [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
                'username' => config('mail.mailers.smtp.username'),
                'password' => config('mail.mailers.smtp.password') ? __('admin.fake_password') : '',
                'from_address' => config('mail.from.address'),
                'requireAuth' => (bool) config('mail.mailers.smtp.require_auth'),
            ],
        ]);
    }
}
