<?php

// TODO - Refactor

namespace App\Http\Controllers\Admin;

use App\Actions\AdministrationMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, AdministrationMenu $menu): Response
    {
        $this->authorize('admin-link');

        return Inertia::render('Admin/Index', [
            'menu' => $menu($request->user()),
        ]);
    }
}
