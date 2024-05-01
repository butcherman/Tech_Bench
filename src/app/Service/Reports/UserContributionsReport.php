<?php

namespace App\Service\Reports;

use App\Http\Requests\Report\User\UserReportRequest;
use App\Interface\ReportsInterface;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\User;

class UserContributionsReport extends Reports
{
    protected $reportData = [];

    public function __construct(protected UserReportRequest $request)
    {
        parent::__construct();
    }

    protected function buildReportData()
    {
        foreach ($this->request->user_list as $user) {
            $user = User::whereUsername($user)->first();

            $userData = [];
            $userData['New Customer Notes'] = $this->runQuery(CustomerNote::class, $user, 'created_by');
            $userData['Updated Customer Notes'] = $this->runQuery(CustomerNote::class, $user, 'updated_by');
            $userData['Customer Files Uploaded'] = $this->runQuery(CustomerFile::class, $user);

            $this->reportData[$user->full_name] = $userData;
        }
    }

    protected function runQuery($model, $user, $userIdField = 'user_id')
    {
        return $model::where($userIdField, $user->user_id)
            ->whereBetween('created_at', [
                $this->request->start_date,
                $this->request->end_date
            ])
            ->count();
    }
}