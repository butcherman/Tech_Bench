<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Inertia\Inertia;

class GetConfigController extends Controller
{
    /**
     * Application Configuration page
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Config', [
            'settings' => [
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'filesize' => config('filesystems.max_filesize'),
                'allowOath' => (bool) config('services.azure.allow_login'),
                'allowRegister' => (bool) config('services.azure.allow_register'),
                'tenantId' => config('services.azure.tenant'),
                'clientId' => config('services.azure.client_id'),
                'clientSecret' => config('services.azure.client_secret') ? __('admin.fake_password') : '',
                'redirectUri' => config('app.url').'/auth/callback',
            ],
            'tz_list' => \Timezonelist::toArray(),
        ]);
    }
}
