<?php

namespace App\Http\Controllers\Admin\Logo;

use Inertia\Inertia;

use App\Models\AppSettings;
use App\Http\Controllers\Controller;

class GetLogoController extends Controller
{
    /**
     * Show the form to upload a new App Logo
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Logo', [
            'logo' => config('app.logo'),
        ]);
    }
}
