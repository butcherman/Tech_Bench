<?php

namespace App\Services\Report\User;

use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * @codeCoverageIgnore
 */
class UserContributionsReport extends UserReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamPage = 'Report/User/Contribution/Index';
        $this->reportDataPage = 'Report/User/Contribution/Show';
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

        $from = $reportParams->get('start_date');
        $to = $reportParams->get('end_date');

        foreach ($userList as $user) {
            $reportData[$user->full_name] = [
                'New Customer Notes' => $this->getContributions(
                    CustomerNote::class,
                    $user,
                    $from,
                    $to,
                    'created_by'
                ),
                'Updated Customer Notes' => $this->getContributions(
                    CustomerNote::class,
                    $user,
                    $from,
                    $to,
                    'updated_by'
                ),
                'Customer Files Uploaded' => $this->getContributions(
                    CustomerFile::class,
                    $user,
                    $from,
                    $to
                ),
                'Tech Tips Created' => $this->getContributions(
                    TechTip::class,
                    $user,
                    $from,
                    $to
                ),
                'Tech Tips Updated' => $this->getContributions(
                    TechTip::class,
                    $user,
                    $from,
                    $to,
                    'updated_id'
                ),
            ];
        }

        return $reportData;
    }

    /**
     * Count the number of related models user has contributed to.
     */
    protected function getContributions($model, $user, $from, $to, $idField = 'user_id'): int
    {
        return $model::where($idField, $user->user_id)
            ->whereBetween('created_at', [$from, $to])
            ->count();
    }
}
