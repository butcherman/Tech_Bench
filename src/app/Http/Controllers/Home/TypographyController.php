<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;

class TypographyController extends Controller
{
    /**
     * Basic page that shows all Vue Components and Page Styles.  This page
     * can only be accessed by an Installer level user and while the app
     * is in Local or Testing mode.
     */
    public function __invoke(Request $request)
    {
        if (
            !$request->user()->role_id == 1
            || !App::environment(['local', 'testing'])
        ) {
            abort(404);
        }

        return Inertia::render('Home/Typography', [
            'user-list' => User::all(),
        ]);
    }
}
