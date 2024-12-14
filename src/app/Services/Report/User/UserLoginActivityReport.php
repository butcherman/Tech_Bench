<?php

namespace App\Services\Report\User;

use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class UserLoginActivityReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamPage = 'Report/User/Activity/Index';
        $this->reportDataPage = 'Report/User/Activity/Show';
        $this->reportParamProps = ['user-list' => User::all()];
    }

    /**
     * Validate the request to run the report.
     */
    public function validateReportParams(Request $request): Collection
    {
        return Validator::make($request->all(), [
            'start_date' => ['required'],
            'end_date' => ['required'],
            'user_list' => ['required'],
        ])->safe()->collect();
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): mixed
    {
        $userList = $this->getUserList($reportParams);
        $reportData = [];

        foreach ($userList as $user) {
            $reportData[$user->full_name] = $user->UserLogins()
                ->whereBetween('created_at', [
                    $reportParams->get('start_date'),
                    $reportParams->get('end_date'),
                ])->get();
        }

        return $reportData;
    }
}
