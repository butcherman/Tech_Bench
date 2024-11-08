<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Misc\BuildAdminMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdministrationController extends Controller
{
    public function __construct(protected BuildAdminMenu $svc) {}

    /**
     * Show the Administration Home Page
     */
    public function __invoke(Request $request): Response
    {
        $this->authorize('admin-link');

        return Inertia::render('Admin/Index', [
            'menu' => fn () => $this->svc->handle($request->user()),
        ]);
    }
}
