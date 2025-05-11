<?php

namespace App\Http\Controllers\Reports;

use App\Contracts\ReportContract;
use App\Contracts\ReportingContract;
use App\Http\Controllers\Controller;
use App\Policies\GatePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Inertia\Inertia;
use Inertia\Response;

class RunReportController extends Controller
{
    /**
     * Run the requested Report
     */
    public function __invoke(Request $request, ReportContract $contract) //: Response
    {
        $this->authorize('reports-link', GatePolicy::class);

        return 'run report';

        // $reportParams = $contract->validateReportParams($request);

        // return Inertia::render($contract->getReportDataPage(), [
        //     'report-params' => $reportParams,
        //     'report-data' => $contract->generateReportData($reportParams),
        //     'report-view' => Blade::render('report.customer.CustomerSummaryReport'),
        // ]);
    }
}
