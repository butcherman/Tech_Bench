<?php

// TODO - Refactor

namespace App\Http\Controllers\Report\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\User\UserReportRequest;
use App\Models\User;
use App\Policies\GatePolicy;
use App\Service\Reports\UserActivityReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserActivityReportController extends Controller
{
    public function index()
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/User/Activity/Index', [
            'user-list' => User::all(),
        ]);

    }

    /**
     * Display the resource.
     */
    public function show(UserReportRequest $request)
    {
        Log::info('User Login Activity Report run by '.$request->user()->username);
        $report = new UserActivityReport($request);

        return Inertia::render('Report/User/Activity/Show', [
            'report-data' => $report->getReportData(),
            'start-date' => Carbon::parse($request->start_date)->format('M d, Y'),
            'end-date' => Carbon::parse($request->end_date)->format('M d, Y'),
        ]);
    }
}
