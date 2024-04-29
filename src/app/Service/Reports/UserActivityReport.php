<?php

namespace App\Service\Reports;

use App\Http\Requests\Report\User\UserReportRequest;
use App\Models\User;
use App\Models\UserLogins;

/**
 * Report will return the login dates and times for the selected users 
 * within the selected time period
 */
class UserActivityReport
{
    protected $reportData = [];

    public function __construct(protected UserReportRequest $request)
    {
        $this->buildReportData();
    }

    public function getReportData()
    {
        return $this->reportData;
    }

    protected function buildReportData()
    {
        foreach ($this->request->user_list as $username) {
            $user = User::whereUsername($username)->first();

            $loginData = UserLogins::where('user_id', $user->user_id)
                ->whereBetween('created_at', [
                    $this->request->start_date,
                    $this->request->end_date
                ])
                ->get();

            $this->reportData[$user->full_name] = $loginData;
        }
    }
}