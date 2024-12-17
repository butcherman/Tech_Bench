<?php

namespace App\Http\Controllers\Report;

use App\Contracts\ReportingContract;
use App\Http\Controllers\Controller;
use App\Policies\GatePolicy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportParametersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReportingContract $contract)
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render(
            $contract->getReportParamPage(),
            $contract->getReportParamProps()
        );
    }
}
