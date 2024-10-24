<?php

namespace App\Service\Reports;

use App\Http\Requests\Report\User\UserPermissionsRequest;
use App\Http\Resources\UserPermissionResource;
use App\Models\User;

class UserPermissionsReport extends Reports
{
    public function __construct(protected UserPermissionsRequest $request)
    {
        parent::__construct();
    }

    public function buildReportData()
    {
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
