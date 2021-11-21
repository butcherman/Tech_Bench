<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetEmailSettingsController extends Controller
{
    /**
     * Form for email settings
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Email', [
            'settings' => [
                'host'         => config('mail.mailers.smtp.host'),
                'port'         => config('mail.mailers.smtp.port'),
                'encryption'   => config('mail.mailers.smtp.encryption'),
                'username'     => config('mail.mailers.smtp.username'),
                'from_address' => config('mail.from.address'),
            ],
        ]);
    }
}
