<?php

// TODO - Refactor

namespace App\Http\Controllers\Report;

use App\Actions\ReportsMenu;
use App\Http\Controllers\Controller;
use App\Policies\GatePolicy;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Show Index page for running reports
     */
    public function __invoke(ReportsMenu $menu)
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/Index', [
            'menu' => $menu->get(),
        ]);
    }
}
