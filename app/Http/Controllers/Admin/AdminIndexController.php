<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Actions\BuildAdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AdminIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('admin-link', $request->user());

        return Inertia::render('Admin/Index', [
            'links' => (new BuildAdminMenu)->build($request->user()),
        ]);
    }
}
