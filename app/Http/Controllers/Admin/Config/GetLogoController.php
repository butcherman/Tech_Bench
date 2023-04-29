<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetLogoController extends Controller
{
    /**
     * Form for changing the application logo
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/App/Logo', [
            'logo' => config('app.logo'),
        ]);
    }
}
