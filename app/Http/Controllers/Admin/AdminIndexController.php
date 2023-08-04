<?php

namespace App\Http\Controllers\Admin;

use App\Actions\BuildAdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

/**
 * Landing page for Application Administration
 */
class AdminIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        Gate::authorize('admin-link', $request->user());

        return Inertia::render('Admin/Index', [
            'links' => (new BuildAdminMenu)->build($request->user()),
        ]);
    }
}
