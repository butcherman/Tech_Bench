<?php

namespace App\Http\Controllers\Admin;

use App\Actions\BuildAdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('admin-link', $request->user());
        $menu = new BuildAdminMenu($request->user());

        return Inertia::render('Admin/Index', [
            'menu' => $menu->getAdminMenu(),
        ]);
    }
}
