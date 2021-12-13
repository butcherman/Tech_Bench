<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Actions\BuildAdminMenu;
use App\Http\Controllers\Controller;

class AdminIndexController extends Controller
{
    /**
     * System Administration Home Page
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('admin-link', $request->user());
        $menuObj = new BuildAdminMenu($request->user());

        return Inertia::render('Admin/Index', [
            'links' => $menuObj->execute(),
        ]);
    }
}
