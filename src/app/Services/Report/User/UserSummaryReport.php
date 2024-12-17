<?php

namespace App\Services\Report\User;

use App\Http\Resources\Reports\Users\UserSummaryResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * @codeCoverageIgnore
 */
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

    /**
     * Validate the request to run the report.
     */
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
}
