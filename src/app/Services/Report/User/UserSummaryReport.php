<?php

namespace App\Services\Report\User;

use App\Http\Resources\Reports\Users\UserSummaryResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class UserSummaryReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamPage = 'Report/User/Details/Index';
        $this->reportDataPage = 'Report/User/Details/Show';
        $this->reportParamProps = ['user-list' => User::all()];
    }

    public function validateReportParams(Request $request): Collection
    {
        return Validator::make($request->all(), [
            'allUsers' => ['required', 'boolean'],
            'disabledUsers' => ['required', 'boolean'],
            'user_list' => ['array', 'required_if:allUsers,false'],
        ])->safe()->collect();
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): AnonymousResourceCollection
    {
        $userList = $this->getUserList($reportParams);

        return UserSummaryResource::collection($userList);
    }

    /**
     * Get the users that are part of the report.
     */
    protected function getUserList(Collection $reportParams): EloquentCollection
    {
        if ($reportParams->get('allUsers')) {
            return User::when($reportParams->get('disabledUsers'), function ($q) {
                return $q->withTrashed();
            })->get();
        }

        return User::whereIn('username', $reportParams->get('user_list'))->get();
    }
}
