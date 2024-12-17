<?php

namespace App\Services\Report\User;

use App\Http\Resources\Reports\Users\UserPermissionsResource;
use App\Models\User;
use App\Models\UserRolePermissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * @codeCoverageIgnore
 */
class UserPermissionsReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamPage = 'Report/User/Permissions/Index';
        $this->reportDataPage = 'Report/User/Permissions/Show';
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
    public function generateReportData(Collection $reportParams): mixed
    {
        $userList = $this->getUserList($reportParams);

        return [
            'permissions' => UserRolePermissionType::all()->groupBy('group'),
            'data' => UserPermissionsResource::collection($userList),
        ];
    }
}
