<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\UserLoginReportRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserLoginReportController extends Controller
{
    /**
     * User Login Report landing page - select options for report
     */
    public function index()
    {
        return Inertia::render('Reports/UserLogin/Index', [
            'user_list' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        return redirect(route('reports.user-login-report.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Run the User Login report
     */
    public function update(UserLoginReportRequest $request, $id)
    {
        //  Get User List
        $userList = [];
        foreach($request->selected_list as $u)
        {
            $user = User::where('username', $u)->first();
            $logins = $user->UserLogins->where('created_at', '>=', $request->start_date)->where('created_at', '<=', $request->stop_date);

            $entry = [];
            foreach($logins as $login)
            {
                $timestamp = strtotime($login->created_at);
                $date = date('M d, Y', $timestamp);
                $time = date('H:i',    $timestamp);

                //  Users can have multiple logins for each date, note the time and IP for each login
                $entry[$date][] = [
                    'time' => $time,
                    'ip'   => $login->ip_address,
                ];
            }

            $userList[$user->full_name] = [
                'data'   => $entry,
                'totals' => $logins->count(),
            ];
        }

        return Inertia::render('Reports/UserLogin/Report', [
            'data' => $userList,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
