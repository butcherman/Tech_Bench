<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Misc\BuildAdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, BuildAdminMenu $svc)
    {
        $this->authorize('admin-link');

        return Inertia::render('Admin/Index', [
            'menu' => fn() => $svc($request->user()),
        ]);
    }
}
