<?php

namespace App\Http\Controllers\Reports;

use App\Actions\Misc\BuildReportsMenu;
use App\Http\Controllers\Controller;
use App\Policies\GatePolicy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Show the Reports Landing Page.
     */
    public function __invoke(BuildReportsMenu $svc): Response
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/Index', [
            'reports' => $svc(),
        ]);
    }
}
