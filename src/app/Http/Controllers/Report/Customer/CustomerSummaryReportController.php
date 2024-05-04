<?php

namespace App\Http\Controllers\Report\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Customer\CustomerSummaryRequest;
use App\Policies\GatePolicy;
use App\Service\Reports\CustomerSummaryReport;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerSummaryReportController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index()
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/Customer/Summary/Index');
    }

    /**
     * Display the resource.
     */
    public function show(CustomerSummaryRequest $request)
    {
        Log::info('Customer Summary Report run by '.$request->user()->username);
        $report = new CustomerSummaryReport($request);

        return Inertia::render('Report/Customer/Summary/Show', [
            'report-data' => $report->getReportData(),
        ]);
    }
}
