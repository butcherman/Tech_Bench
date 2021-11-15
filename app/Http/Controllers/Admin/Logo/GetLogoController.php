<?php

namespace App\Http\Controllers\Admin\Logo;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
