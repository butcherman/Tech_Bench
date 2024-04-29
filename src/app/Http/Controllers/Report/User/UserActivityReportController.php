<?php

namespace App\Http\Controllers\Report\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\User\UserActivityReportRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserActivityReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Report/User/Activity/Index', [
            'user-list' => User::all(),
        ]);

    }

    /**
     * Display the resource.
     */
    public function show(UserActivityReportRequest $request)
    {
        Log::info('User Login Activity Report run by ' . $request->user()->username);

        return Inertia::render('Report/User/Activity/Show', [
            'report-data' => $request->fetchReportData(),
            'start-date' => Carbon::parse($request->start_date)->format('M d, Y'),
            'end-date' => Carbon::parse($request->end_date)->format('M d, Y'),
        ]);
    }
}
