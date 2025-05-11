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
    public function __invoke(Request $request, ReportContract $contract): Response
    {
        $this->authorize('reports-link', GatePolicy::class);

        $view = $contract->getReportGroup() . '.' . $contract->getReportDataPage();

        if ($request->getMethod() === 'PUT') {
            $reportParams = $contract->validateReportParams($request);
            session()->flash('params', $reportParams);
        } else {
            $data = $contract->generateReportData(
                session()->get('params', collect(['data']))
            );
        }

        return Inertia::render('Report/Run', [
            'template' => Inertia::defer(fn() => Blade::render(
                'report.' . $view,
                [
                    'data' => $data,
                ]
            )),
        ]);
    }
}
