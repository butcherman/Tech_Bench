<?php

namespace App\Http\Controllers\Admin\Config;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;

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
