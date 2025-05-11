<?php

namespace App\Http\Controllers\Reports;

use App\Contracts\ReportContract;
use App\Http\Controllers\Controller;
use App\Policies\GatePolicy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportParametersController extends Controller
{
    /**
     * Show the form to collect information about the requested report.
     */
    public function __invoke(ReportContract $contract): Response
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render(
            'Report/Get',
            [
                'group' => $contract->getReportGroup(),
                'form' => $contract->getReportParamForm(),
                'props' => $contract->getReportParamProps(),
            ]
        );
    }
}
