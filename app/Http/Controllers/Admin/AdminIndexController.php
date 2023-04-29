<?php

namespace App\Http\Controllers\Admin;

use App\Actions\BuildAdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AdminIndexController extends Controller
{
    /**
     * System Administration Home Page
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('admin-link', $request->user());

        return Inertia::render('Admin/Index', [
            'links' => (new BuildAdminMenu($request->user()))->execute(),
        ]);
    }
}
