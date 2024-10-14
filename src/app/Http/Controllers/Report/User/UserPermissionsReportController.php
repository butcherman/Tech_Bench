<?php

// TODO - Refactor

namespace App\Http\Controllers\Report\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\User\UserPermissionsRequest;
use App\Models\User;
use App\Models\UserRolePermissionType;
use App\Policies\GatePolicy;
use App\Service\Reports\UserPermissionsReport;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserPermissionsReportController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index()
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/User/Permissions/Index', [
            'user-list' => User::all(),
        ]);
    }

    /**
     * Display the resource.
     */
    public function show(UserPermissionsRequest $request)
    {
        Log::info('User Permissions Report run by '.$request->user()->username);
        $report = new UserPermissionsReport($request);

        return Inertia::render('Report/User/Permissions/Show', [
            'report-data' => $report->getReportData(),
            'permission-list' => UserRolePermissionType::all()->groupBy('group'),
        ]);
    }
}
