<?php

namespace App\Http\Controllers\Report;

use App\Contracts\ReportingContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportParametersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ReportingContract $contract)
    {
        return Inertia::render(
            $contract->getReportParamPage(),
            $contract->getReportParamProps()
        );
    }
}
