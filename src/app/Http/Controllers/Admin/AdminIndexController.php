<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\BuildAdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, BuildAdminMenu $svc): Response
    {
        $this->authorize('admin-link');

        return Inertia::render('Admin/Index', [
            'menu' => fn () => $svc($request->user()),
        ]);
    }
}
