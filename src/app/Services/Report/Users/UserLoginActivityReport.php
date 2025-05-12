<?php

namespace App\Services\Report\Users;

use App\Models\User;
use Illuminate\Support\Collection;

class UserLoginActivityReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'UserLoginActivityReportForm';
        $this->reportDataPage = 'UserLoginActivityReport';
        $this->reportParamProps = ['user-list' => User::all()];
    }

    /**
     * Validate the request to run the report.
     */
    public function getValidationParams(): array
    {
        return [
            'start_date' => ['required', 'string'],
            'end_date' => ['required', 'string'],
            'user_list' => ['required', 'array'],
        ];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $data = [];
        $start = $reportParams->get('start_date');
        $end = $reportParams->get('end_date');

        foreach ($reportParams->get('user_list') as $username) {
            $user = User::whereUsername($username)->first();

            $loginData = $user->UserLogins()->whereBetween('created_at', [
                $start,
                $end
            ])->get();

            $data[$user->full_name] = $loginData->toArray();
        }

        return $data;
    }
}
