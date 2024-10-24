<?php

// TODO - Refactor

namespace App\Http\Controllers\Report\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\Customer\CustomerFilesRequest;
use App\Policies\GatePolicy;
use App\Service\Cache;
use App\Service\Reports\CustomerFilesReport;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerFilesReportController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index()
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/Customer/Files/Index', [
            'file-types' => Cache::fileTypes(),
        ]);
    }

    /**
     * Display the resource.
     */
    public function show(CustomerFilesRequest $request)
    {
        Log::info('Customer Files Report run by '.$request->user()->username);
        $report = new CustomerFilesReport($request);

        return Inertia::render('Report/Customer/Files/Show', [
            'report-data' => $report->getReportData(),
            'has-tag' => $request->hasInput,
        ]);
    }
}
