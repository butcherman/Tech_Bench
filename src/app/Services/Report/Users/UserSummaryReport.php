<?php

namespace App\Services\Report\Users;

use App\Models\User;
use Illuminate\Support\Collection;

class UserSummaryReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'UserSummaryReportForm';
        $this->reportDataPage = 'UserSummaryReport';
        $this->reportParamProps = ['user-list' => User::all()];
    }

    /**
     * Validate the request to run the report.
     */
    public function getValidationParams(): array
    {
        return [
            'all_users' => ['required', 'boolean'],
            'disabled_users' => ['required', 'boolean'],
            'user_list' => ['array'],
        ];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $data = [];
        $userList = $this->getUserList($reportParams);

        foreach ($userList as $user) {
            $data[] = [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role' => $user->UserRole->name,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => $user->deleted_at,
                'password_expires' => $user->password_expires,
                'last_login' => $user->getLastLogin()->created_at ?? 'Never',
            ];
        }

        return $data;
    }
}
