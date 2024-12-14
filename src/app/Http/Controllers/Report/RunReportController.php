<?php

namespace App\Http\Controllers\Report;

use App\Contracts\ReportingContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RunReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ReportingContract $contract)
    {
        $reportParams = $contract->validateReportParams($request);

        return Inertia::render($contract->getReportDataPage(), [
            'report-data' => $contract->generateReportData($reportParams),
        ]);
    }
}
