<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RunReportController extends Controller
{
    /**
     * Run the requested Report
     */
    public function __invoke(Request $request)
    {
        //
        return 'run report';
    }
}
