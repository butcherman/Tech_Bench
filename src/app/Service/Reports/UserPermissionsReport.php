<?php

namespace App\Service\Reports;

use App\Http\Requests\Report\User\UserPermissionsRequest;
use App\Http\Resources\UserPermissionResource;
use App\Interface\ReportsInterface;
use App\Models\User;
use App\Models\UserRolePermissionType;

class UserPermissionsReport extends Reports
{
    public function __construct(protected UserPermissionsRequest $request)
    {
        parent::__construct();
    }

    public function buildReportData()
    {
        $permissions = UserRolePermissionType::all()->groupBy('group');
        $userList = $this->getUserList();


        $this->reportData = UserPermissionResource::collection($this->getUserList());
    }

    protected function getUserList()
    {
        if ($this->request->allUsers) {
            if ($this->request->disabledUsers) {
                return User::withTrashed()->get();
            }

            return User::all();
        }

        $userList = [];
        foreach ($this->request->user_list as $user) {
            $userList[] = User::whereUsername($user)->first();
        }

        return $userList;
    }
}