<?php

// TODO - Refactor

namespace App\Http\Controllers\Report\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\User\UserPermissionsRequest;
use App\Models\User;
use App\Policies\GatePolicy;
use App\Service\Reports\UserDetailsReport;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserDetailsReportController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index()
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/User/Details/Index', [
            'user-list' => User::all(),
        ]);
    }

    /**
     * Display the resource.
     */
    public function show(UserPermissionsRequest $request)
    {
        Log::info('User Details Report run by '.$request->user()->username);
        $report = new UserDetailsReport($request);

        return Inertia::render('Report/User/Details/Show', [
            'report-data' => $report->getReportData(),
        ]);
    }
}
